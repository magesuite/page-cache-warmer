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
    /**
     * @var \MageSuite\PageCacheWarmer\Service\EntityTagsCreator
     */
    private $entityTagsCreator;

    public function __construct(
        \MageSuite\PageCacheWarmer\Service\WarmupEntityCreator $warmupEntityCreator,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \MageSuite\PageCacheWarmer\Helper\Configuration $configuration,
        \MageSuite\PageCacheWarmer\Service\EntityTagsCreator $entityTagsCreator
    )
    {
        $this->warmupEntityCreator = $warmupEntityCreator;
        $this->scopeConfig = $scopeConfig;
        $this->configuration = $configuration;
        $this->entityTagsCreator = $entityTagsCreator;
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

    public function prepareAndSaveEntityTags($id, $type, $tags)
    {
        $entityTagsCreator = $this->entityTagsCreator;

        $data = $entityTagsCreator->prepareEntity($id, $type, $tags);

        $entityTagsCreator->saveEntity($data);
    }
}