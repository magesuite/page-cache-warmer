<?php
namespace MageSuite\PageCacheWarmer\Observer;

class CacheFlush implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var \MageSuite\PageCacheWarmer\Service\CronScheduler
     */
    private $cronScheduler;
    /**
     * @var \MageSuite\PageCacheWarmer\Helper\Configuration
     */
    private $configuration;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\WarmupQueue\Url\CollectionFactory
     */
    private $pageWarmerCollectionFactory;

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

        $pageWarmerCollection->walk('delete');

        $this->cronScheduler->schedule();
    }
}