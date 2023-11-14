<?php
namespace MageSuite\PageCacheWarmer\Helper;

class Configuration
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

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

    public function isGatheringTagsEnabled()
    {
        $configuration = $this->getConfiguration();

        return $configuration['gather_tags_from_url'];
    }

    public function getConfiguration()
    {
        return [
            'is_enabled' => $this->scopeConfig->getValue('cache_warmer/general/enabled'),
            'gather_tags_from_url' => $this->scopeConfig->getValue('cache_warmer/general/gather_tags_from_url'),
            'store_views' => explode(',', $this->scopeConfig->getValue('cache_warmer/general/store_view')),
            'customer_groups' => explode(',', $this->scopeConfig->getValue('cache_warmer/general/customer_group'))
        ];
    }
}
