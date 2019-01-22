<?php

namespace MageSuite\PageCacheWarmer\Console\Command;

class GenerateCleanupUrls extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var \MageSuite\PageCacheWarmer\Service\GenerateCleanupUrlsFactory
     */
    protected $generateCleanupUrlsFactory;

    public function __construct(\MageSuite\PageCacheWarmer\Service\GenerateCleanupUrlsFactory $generateCleanupUrlsFactory)
    {
        parent::__construct();
        $this->generateCleanupUrlsFactory = $generateCleanupUrlsFactory;
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
        $this->generateCleanupUrlsFactory->create()->generate();
    }
}


