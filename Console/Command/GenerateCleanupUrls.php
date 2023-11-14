<?php

namespace MageSuite\PageCacheWarmer\Console\Command;

class GenerateCleanupUrls extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var \MageSuite\PageCacheWarmer\Service\CleanedUrlsGeneratorFactory
     */
    protected $cleanedUrlsGeneratorFactory;

    public function __construct(\MageSuite\PageCacheWarmer\Service\CleanedUrlsGeneratorFactory $cleanedUrlsGeneratorFactory)
    {
        parent::__construct();
        $this->cleanedUrlsGeneratorFactory = $cleanedUrlsGeneratorFactory;
    }

    protected function configure()
    {
        $this
            ->setName('cache:cleanup:generate')
            ->setDescription('Generate cleanup urls');
    }

    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    )
    {
        $this->cleanedUrlsGeneratorFactory->create()->generate();
    }
}
