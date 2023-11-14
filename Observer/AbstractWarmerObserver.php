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
    protected $scopeConfig;
    /**
     * @var \MageSuite\PageCacheWarmer\Helper\Configuration
     */
    protected $configuration;
    /**
     * @var \MageSuite\PageCacheWarmer\Service\AssociatedWarmupEntityCreator
     */
    protected $associatedWarmupEntityCreator;

    public function __construct(
        \MageSuite\PageCacheWarmer\Service\WarmupEntityCreator $warmupEntityCreator,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \MageSuite\PageCacheWarmer\Helper\Configuration $configuration,
        \MageSuite\PageCacheWarmer\Service\AssociatedWarmupEntityCreator $associatedWarmupEntityCreator
    )
    {
        $this->warmupEntityCreator = $warmupEntityCreator;
        $this->scopeConfig = $scopeConfig;
        $this->configuration = $configuration;
        $this->associatedWarmupEntityCreator = $associatedWarmupEntityCreator;
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

    public function prepareAndSaveEntityAssociatedUrls($tags)
    {
        $associatedWarmupEntityCreator = $this->associatedWarmupEntityCreator;

        $associatedWarmupEntityCreator->addAssociatedUrls($tags);

    }
}
