<?php
namespace MageSuite\PageCacheWarmer\Service;

class CleanedTagsQueueManager
{
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceConnection
    )
    {
        $this->resourceConnection = $resourceConnection;
    }

    public function addTagsToCleanupQueue($tags)
    {
        if(!is_array($tags)){
            $tags = [$tags];
        }

        $connection = $this->resourceConnection->getConnection();
        $select = $connection->select()
            ->from('varnish_cache_tags')
            ->where('tag IN(?)', $tags);

        $result = $connection->fetchAll($select);

        if(!$result){
            return;
        }

        $insertData = [];
        foreach ($result as $tag){
            $insertData[] = $tag['id'];
        }

        $connection->insertArray(
            $connection->getTableName('varnish_cache_cleanup_queue'),
            ['tag'],
            $insertData,
            \Magento\Framework\DB\Adapter\Pdo\Mysql::INSERT_IGNORE
        );
    }
}