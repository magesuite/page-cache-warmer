<?php
namespace MageSuite\PageCacheWarmer\Cron;

class RegenerateUrls
{
    /**
     * @var \MageSuite\PageCacheWarmer\Service\RegenerateUrls
     */
    private $regenerateUrls;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var \MageSuite\PageCacheWarmer\Helper\Configuration
     */
    private $configuration;

    public function __construct(
        \MageSuite\PageCacheWarmer\Service\RegenerateUrls $regenerateUrls,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \MageSuite\PageCacheWarmer\Helper\Configuration $configuration
    )
    {
        $this->regenerateUrls = $regenerateUrls;
        $this->scopeConfig = $scopeConfig;
        $this->configuration = $configuration;
    }

    public function execute()
    {
        if (!$this->configuration->isCacheWarmerEnabled()){
            return;
        }

        $this->regenerateUrls->regenerate();
    }
}