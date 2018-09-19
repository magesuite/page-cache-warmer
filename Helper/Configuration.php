<?php
namespace MageSuite\PageCacheWarmer\Helper;

class Configuration
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function isCacheWarmerEnabled()
    {
        $configuration = $this->getConfiguration();

        return $configuration['is_enabled'];
    }

    public function getConfiguration()
    {
        return [
            'is_enabled' => $this->scopeConfig->getValue('cache_warmer/general/enabled'),
            'store_views' => explode(',', $this->scopeConfig->getValue('cache_warmer/general/store_view')),
            'customer_groups' => explode(',', $this->scopeConfig->getValue('cache_warmer/general/customer_group'))
        ];
    }
}