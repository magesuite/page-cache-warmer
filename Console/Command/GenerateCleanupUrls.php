<?php

namespace MageSuite\PageCacheWarmer\Console\Command;

class GenerateCleanupUrls extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var \MageSuite\PageCacheWarmer\Service\CleanupUrlsGeneratorFactory
     */
    protected $cleanupUrlsGeneratorFactory;

    public function __construct(\MageSuite\PageCacheWarmer\Service\CleanupUrlsGeneratorFactory $cleanupUrlsGeneratorFactory)
    {
        parent::__construct();
        $this->cleanupUrlsGeneratorFactory = $cleanupUrlsGeneratorFactory;
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
        $this->cleanupUrlsGeneratorFactory->create()->generate();
    }
}


