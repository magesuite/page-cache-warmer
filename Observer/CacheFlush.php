<?php
/**
 * Created by PhpStorm.
 * User: blazejdoleska
 * Date: 05/09/2018
 * Time: 15:28
 */

namespace Creativestyle\MageSuite\PageCacheWarmer\Observer;


class CacheFlush implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Creativestyle\MageSuite\PageCacheWarmer\Service\RegenerateUrls
     */
    private $regenerateUrls;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        \Creativestyle\MageSuite\PageCacheWarmer\Service\RegenerateUrls $regenerateUrls,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->regenerateUrls = $regenerateUrls;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->scopeConfig->getValue('cache_warmer/general/enabled')){
            return;
        }

        $this->regenerateUrls->regenerate();
    }
}