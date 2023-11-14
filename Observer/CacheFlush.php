<?php
namespace MageSuite\PageCacheWarmer\Observer;

class CacheFlush implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var \MageSuite\PageCacheWarmer\Service\CronScheduler
     */
    protected $cronScheduler;
    /**
     * @var \MageSuite\PageCacheWarmer\Helper\Configuration
     */
    protected $configuration;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\WarmupQueue\Url\CollectionFactory
     */
    protected $pageWarmerCollectionFactory;

    public function __construct(
        \MageSuite\PageCacheWarmer\Service\CronScheduler $cronScheduler,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \MageSuite\PageCacheWarmer\Helper\Configuration $configuration,
        \MageSuite\PageCacheWarmer\Model\ResourceModel\WarmupQueue\Url\CollectionFactory $pageWarmerCollectionFactory
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->cronScheduler = $cronScheduler;
        $this->configuration = $configuration;
        $this->pageWarmerCollectionFactory = $pageWarmerCollectionFactory;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->configuration->isCacheWarmerEnabled()){
            return;
        }

        $pageWarmerCollection = $this->pageWarmerCollectionFactory->create();

        $pageWarmerCollection->getConnection()
            ->delete($pageWarmerCollection->getMainTable());

        $this->cronScheduler->schedule();
    }
}
