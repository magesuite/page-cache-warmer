<?php
namespace MageSuite\PageCacheWarmer\Service;

class AssociatedUrlsGenerator
{
    protected $entityTypeMap = [
        'product' => 'product',
        'category' => 'category',
        'page' => 'cms-page',
        'index' => 'cms-page'
    ];
    /**
     * @var \MageSuite\PageCacheWarmer\Model\Entity\UrlFactory
     */
    protected $urlsFactory;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\Entity\UrlRepository
     */
    protected $urlsRepository;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;


    public function __construct(
        \MageSuite\PageCacheWarmer\Model\Entity\UrlFactory $urlsFactory,
        \MageSuite\PageCacheWarmer\Model\Entity\UrlRepository $urlsRepository,
        \Magento\Framework\App\ResourceConnection $resourceConnection
    )
    {
        $this->urlsFactory = $urlsFactory;
        $this->urlsRepository = $urlsRepository;
        $this->resourceConnection = $resourceConnection;
    }

    public function addTagToUrlRelations($value, $controller, $url, $params)
    {
        $this->addTags($value);

        $this->addUrls($controller, $url, $params);

        $this->generateRelations($value, $url);
    }

    public function addTags($tags)
    {
        $tags = explode(',', $tags);

        $connection = $this->resourceConnection->getConnection();

        foreach ($tags as $tag) {
            $insertData[] = [
                'tag' => $tag,
            ];
        }
        $connection->insertArray(
            $connection->getTableName('varnish_cache_tags'),
            ['tag'],
            $tags,
            \Magento\Framework\DB\Adapter\Pdo\Mysql::INSERT_IGNORE
        );
    }

    public function addUrls($controller, $url, $params)
    {
        $entityId = isset($params['id']) ? $params['id'] : null;
        $entityType = $this->getEntityType($controller);

        $connection = $this->resourceConnection->getConnection();

        $insertData[] = [
            'entity_id' => $entityId,
            'entity_type' => $entityType,
            'url' => $url
        ];

        $connection->insertArray(
            $connection->getTableName('varnish_cache_url_tags'),
            ['entity_id', 'entity_type', 'url'],
            $insertData,
            \Magento\Framework\DB\Adapter\Pdo\Mysql::INSERT_IGNORE
        );
    }

    public function generateRelations($tags, $url)
    {
        $tags = explode(',', $tags);

        $urlData = $this->urlsRepository->getByUrl($url);

        $insertData = [];

        $connection = $this->resourceConnection->getConnection();

        $select = $connection->select()
            ->from('varnish_cache_tags')
            ->where('tag IN(?)', $tags);

        $result = $connection->fetchAll($select);

        if(!$result){
            return;
        }

        foreach ($result as $tag) {
            $insertData[] = [
                'tag_id' => $tag['id'],
                'url_id' => $urlData->getId()
            ];
        }

        $connection = $this->resourceConnection->getConnection();

        $connection->insertArray(
            $connection->getTableName('varnish_cache_relations'),
            ['tag_id', 'url_id'],
            $insertData,
            \Magento\Framework\DB\Adapter\Pdo\Mysql::INSERT_IGNORE
        );
    }

    protected function getEntityType($controllerName)
    {
        $entityTypeMap = $this->entityTypeMap;

        return $entityTypeMap[$controllerName];
    }
}
