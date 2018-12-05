<?php

namespace MageSuite\PageCacheWarmer\Console\Command;

class RegenerateUrls extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var \MageSuite\PageCacheWarmer\Service\RegenerateUrlsFactory
     */
    private $regenerateUrlsFactory;

    public function __construct(\MageSuite\PageCacheWarmer\Service\RegenerateUrlsFactory $regenerateUrlsFactory)
    {
        parent::__construct();
        $this->regenerateUrlsFactory = $regenerateUrlsFactory;
    }

    protected function configure()
    {
        $this
            ->setName('cache:warmer:regenerate')
            ->setDescription('Regenerate urls manually');
    }

    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    )
    {

        $this->regenerateUrlsFactory->create()->regenerate();
    }
}


