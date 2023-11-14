<?php
namespace MageSuite\PageCacheWarmer\Service;

class AssociatedWarmupEntityCreator
{

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\Entity\TagRepository
     */
    protected $tagsRepository;
    /**
     * @var \MageSuite\PageCacheWarmer\Helper\Configuration
     */
    protected $configuration;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \MageSuite\PageCacheWarmer\Model\Entity\TagRepository $tagsRepository,
        \MageSuite\PageCacheWarmer\Helper\Configuration $configuration
    )
    {
        $this->resourceConnection = $resourceConnection;
        $this->tagsRepository = $tagsRepository;
        $this->configuration = $configuration;
    }

    public function addAssociatedUrls($tags)
    {
        $configuration = $this->configuration->getConfiguration();
        $tagsIds = $this->getTagsIds($tags);

        if(!$tagsIds){
            return;
        }
        $connection = $this->resourceConnection->getConnection();

        $select = $connection->select()
            ->from(
                ['main_table' => 'varnish_cache_relations'],
                ['']
            )
            ->where('main_table.tag_id IN(?)', $tagsIds);

        $select->joinLeft(
            [ 'urls' => 'varnish_cache_url_tags'],
            'main_table.url_id = urls.id',
            [
                'entity_id',
                'entity_type',
                'url'
            ]
        );

        $select->joinLeft(
                ['customer_group' => 'customer_group'],
                'main_table.id',
                ['customer_group' => 'customer_group_id']
            )
            ->where('customer_group.customer_group_id IN(?)', $configuration['customer_groups']);

        $insertQuery = $connection->insertFromSelect(
            $select,
            'cache_warmup_queue',
            [
                'entity_id',
                'entity_type',
                'url',
                'customer_group'
            ]
        );

        $connection->query($insertQuery);
    }

    public function getTagsIds($tags)
    {
        $ids = [];

        foreach ($tags as $tag) {
            $tagData = $this->tagsRepository->getByTag($tag);

            if (!$tagData) {
               continue;
            }

            $ids[] = $tagData->getId();
        }

        return $ids;
    }

}
