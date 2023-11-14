<?php
namespace MageSuite\PageCacheWarmer\Cron;

class GenerateUrlsBasedOnCleanedTags
{
    /**
     * @var \MageSuite\PageCacheWarmer\Helper\Configuration
     */
    protected $configuration;
    /**
     * @var \MageSuite\PageCacheWarmer\Service\CleanedUrlsGeneratorFactory
     */
    protected $cleanedUrlsGeneratorFactory;


    public function __construct(
        \MageSuite\PageCacheWarmer\Helper\Configuration $configuration,
        \MageSuite\PageCacheWarmer\Service\CleanedUrlsGeneratorFactory $cleanedUrlsGeneratorFactory
    )
    {
        $this->configuration = $configuration;
        $this->cleanedUrlsGeneratorFactory = $cleanedUrlsGeneratorFactory;
    }

    public function execute()
    {
        if (!$this->configuration->isCacheWarmerEnabled()){
            return;
        }

        $this->cleanedUrlsGeneratorFactory->create()->generate();
    }
}
