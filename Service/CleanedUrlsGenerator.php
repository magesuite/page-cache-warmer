<?php
namespace MageSuite\PageCacheWarmer\Service;

class CleanedUrlsGenerator
{
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;
    /**
     * @var \MageSuite\PageCacheWarmer\Helper\Configuration
     */
    protected $configuration;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \MageSuite\PageCacheWarmer\Helper\Configuration $configuration
    )
    {
        $this->resourceConnection = $resourceConnection;
        $this->configuration = $configuration;
    }

    public function generate()
    {
        $tagIds = $this->getTags();

        $this->insert($tagIds);

        $this->removeProcessedTags($tagIds);
    }

    public function insert($tagsIds)
    {
        $configuration = $this->configuration->getConfiguration();

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

    public function getTags()
    {
        $connection = $this->resourceConnection->getConnection();
        $select = $connection->select()
            ->from('varnish_cache_cleanup_queue');

        $result = $connection->fetchAll($select);

        if(empty($result)){
            return [];
        }

        $tags = [];

        foreach ($result as $tag){
            $tags[] = $tag['tag'];

        }

        return $tags;
    }

    public function removeProcessedTags($tags)
    {
        $connection = $this->resourceConnection->getConnection();

        $tableName = $this->resourceConnection->getTableName('varnish_cache_cleanup_queue');

        $where = $connection->quoteInto('tag IN(?) ', $tags);

        $connection->delete($tableName, $where);
    }
}
