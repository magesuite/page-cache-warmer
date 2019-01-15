<?php
namespace MageSuite\PageCacheWarmer\Cron;

class RegenerateUrls
{
    /**
     * @var \MageSuite\PageCacheWarmer\Service\RegenerateUrlsFactory
     */
    protected $regenerateUrlsFactory;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var \MageSuite\PageCacheWarmer\Helper\Configuration
     */
    protected $configuration;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(
        \MageSuite\PageCacheWarmer\Service\RegenerateUrlsFactory $regenerateUrlsFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \MageSuite\PageCacheWarmer\Helper\Configuration $configuration,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->regenerateUrlsFactory = $regenerateUrlsFactory;
        $this->scopeConfig = $scopeConfig;
        $this->configuration = $configuration;
        $this->logger = $logger;
    }

    public function execute()
    {
        if (!$this->configuration->isCacheWarmerEnabled()){
            return;
        }

        $this->regenerateUrlsFactory->create()->regenerate();
    }
}