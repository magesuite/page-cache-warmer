<?php
namespace MageSuite\PageCacheWarmer\Observer;


class AbstractWarmerObserver
{
    /**
     * @var \MageSuite\PageCacheWarmer\Service\WarmupEntityCreator
     */
    protected $warmupEntityCreator;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        \MageSuite\PageCacheWarmer\Service\WarmupEntityCreator $warmupEntityCreator,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->warmupEntityCreator = $warmupEntityCreator;
        $this->scopeConfig = $scopeConfig;
    }

    public function prepareAndSaveEntity($id, $priority, $type)
    {
        $warmupEntityCreator = $this->warmupEntityCreator;

        $data = $warmupEntityCreator->prepareEntity($id, $priority, $type);

        $warmupEntityCreator->saveEntity($data);
    }

    public function getIsCrawlerEnabled()
    {
        return $this->scopeConfig->getValue('cache_warmer/general/enabled');
    }
}