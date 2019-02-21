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

        foreach ($tags as $tag) {
            $this->addTag($tag);
        }
    }

    public function addTag($tag)
    {
        $connection = $this->resourceConnection->getConnection();
        $select = $connection->select()
            ->from('varnish_cache_tags')
            ->where('tag =?', $tag);

        $result = $connection->fetchOne($select);

        if(!$result){
            return;
        }

        $connection->insertArray(
            $connection->getTableName('varnish_cache_cleanup_queue'),
            ['tag'],
            [$result],
            \Magento\Framework\DB\Adapter\Pdo\Mysql::INSERT_IGNORE
        );
    }

}