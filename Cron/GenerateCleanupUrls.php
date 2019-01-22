<?php
namespace MageSuite\PageCacheWarmer\Cron;

class GenerateCleanupUrls
{
    /**
     * @var \MageSuite\PageCacheWarmer\Helper\Configuration
     */
    protected $configuration;
    /**
     * @var \MageSuite\PageCacheWarmer\Service\GenerateCleanupUrlsFactory
     */
    protected $generateCleanupUrlsFactory;


    public function __construct(
        \MageSuite\PageCacheWarmer\Helper\Configuration $configuration,
        \MageSuite\PageCacheWarmer\Service\GenerateCleanupUrlsFactory $generateCleanupUrlsFactory
    )
    {
        $this->configuration = $configuration;
        $this->generateCleanupUrlsFactory = $generateCleanupUrlsFactory;
    }

    public function execute()
    {
        if (!$this->configuration->isCacheWarmerEnabled()){
            return;
        }

        $this->generateCleanupUrlsFactory->create()->generate();
    }
}