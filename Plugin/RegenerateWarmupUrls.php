<?php
namespace MageSuite\PageCacheWarmer\Plugin;

class RegenerateWarmupUrls
{
    /**
     * @var \MageSuite\PageCacheWarmer\Service\CronScheduler
     */
    private $cronScheduler;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\WarmupQueue\Url\CollectionFactory
     */
    private $pageWarmerCollectionFactory;
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    private $resourceConnection;

    public function __construct(
        \MageSuite\PageCacheWarmer\Service\CronScheduler $cronScheduler,
        \MageSuite\PageCacheWarmer\Model\ResourceModel\WarmupQueue\Url\CollectionFactory $pageWarmerCollectionFactory,
        \Magento\Framework\App\ResourceConnection $resourceConnection
    )
    {
        $this->cronScheduler = $cronScheduler;
        $this->pageWarmerCollectionFactory = $pageWarmerCollectionFactory;
        $this->resourceConnection = $resourceConnection;
    }

    public function afterCleanType(\Magento\Framework\App\Cache\TypeList $subject, $result, $typeCode)
    {
        $isTableExist = $this->resourceConnection->getConnection()->isTableExists('cache_warmup_queue');
        if ($typeCode == 'full_page' && $isTableExist) {
            $pageWarmerCollection = $this->pageWarmerCollectionFactory->create();

            $pageWarmerCollection->walk('delete');

            $this->cronScheduler->schedule();
        }

        return $result;
    }
}