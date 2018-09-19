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
    /**
     * @var \MageSuite\PageCacheWarmer\Helper\Configuration
     */
    private $configuration;

    public function __construct(
        \MageSuite\PageCacheWarmer\Service\WarmupEntityCreator $warmupEntityCreator,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \MageSuite\PageCacheWarmer\Helper\Configuration $configuration
    )
    {
        $this->warmupEntityCreator = $warmupEntityCreator;
        $this->scopeConfig = $scopeConfig;
        $this->configuration = $configuration;
    }

    public function prepareAndSaveEntity($id, $priority, $type)
    {
        $warmupEntityCreator = $this->warmupEntityCreator;

        $data = $warmupEntityCreator->prepareEntity($id, $priority, $type);

        $warmupEntityCreator->saveEntity($data);
    }

    public function getIsCrawlerEnabled()
    {
        return $this->configuration->isCacheWarmerEnabled();
    }
}