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

    public function __construct(
        \MageSuite\PageCacheWarmer\Service\RegenerateUrls $regenerateUrls,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->regenerateUrls = $regenerateUrls;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute()
    {
        if (!$this->scopeConfig->getValue('cache_warmer/general/enabled')){
            return;
        }

        $this->regenerateUrls->regenerate();
    }
}