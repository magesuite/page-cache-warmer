<?php
/**
 * Created by PhpStorm.
 * User: blazejdoleska
 * Date: 03/09/2018
 * Time: 18:52
 */

namespace Creativestyle\MageSuite\PageCacheWarmer\Observer;


class AbstractWarmerObserver
{
    const PRODUCT_ENTITY = 'product';

    const CATEGORY_ENTITY = 'category';

    const CMS_ENTITY = 'cms-page';
    /**
     * @var \Creativestyle\MageSuite\PageCacheWarmer\Service\WarmupEntityCreator
     */
    protected $warmupEntityCreator;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        \Creativestyle\MageSuite\PageCacheWarmer\Service\WarmupEntityCreator $warmupEntityCreator,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->warmupEntityCreator = $warmupEntityCreator;
        $this->scopeConfig = $scopeConfig;
    }

    public function getIsCrawlerEnabled()
    {
        return $this->scopeConfig->getValue('cache_warmer/general/enabled');
    }
}