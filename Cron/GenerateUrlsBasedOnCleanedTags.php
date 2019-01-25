<?php
namespace MageSuite\PageCacheWarmer\Cron;

class GenerateUrlsBasedOnCleanedTags
{
    /**
     * @var \MageSuite\PageCacheWarmer\Helper\Configuration
     */
    protected $configuration;
    /**
     * @var \MageSuite\PageCacheWarmer\Service\CleanupUrlsGeneratorFactory
     */
    protected $cleanupUrlsGeneratorFactory;


    public function __construct(
        \MageSuite\PageCacheWarmer\Helper\Configuration $configuration,
        \MageSuite\PageCacheWarmer\Service\CleanupUrlsGeneratorFactory $cleanupUrlsGeneratorFactory
    )
    {
        $this->configuration = $configuration;
        $this->cleanupUrlsGeneratorFactory = $cleanupUrlsGeneratorFactory;
    }

    public function execute()
    {
        if (!$this->configuration->isCacheWarmerEnabled()){
            return;
        }

        $this->cleanupUrlsGeneratorFactory->create()->generate();
    }
}