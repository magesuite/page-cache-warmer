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

    public function __construct(
        \MageSuite\PageCacheWarmer\Service\CronScheduler $cronScheduler,
        \MageSuite\PageCacheWarmer\Model\ResourceModel\WarmupQueue\Url\CollectionFactory $pageWarmerCollectionFactory
    )
    {
        $this->cronScheduler = $cronScheduler;
        $this->pageWarmerCollectionFactory = $pageWarmerCollectionFactory;
    }

    public function afterCleanType(\Magento\Framework\App\Cache\TypeList $subject, $result, $typeCode)
    {
        if ($typeCode == 'full_page') {
            $pageWarmerCollection = $this->pageWarmerCollectionFactory->create();

            $pageWarmerCollection->walk('delete');

            $this->cronScheduler->schedule();
        }

        return $result;
    }
}