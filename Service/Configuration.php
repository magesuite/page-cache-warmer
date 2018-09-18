<?php
namespace MageSuite\PageCacheWarmer\Service;

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

    public function getConfiguration()
    {
        return [
            'store_views' => explode(',', $this->scopeConfig->getValue('cache_warmer/general/store_view')),
            'customer_groups' => explode(',', $this->scopeConfig->getValue('cache_warmer/general/customer_group'))
        ];
    }
}