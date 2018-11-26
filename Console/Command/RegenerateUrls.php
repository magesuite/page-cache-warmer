<?php

namespace MageSuite\PageCacheWarmer\Console\Command;

class RegenerateUrls extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var \MageSuite\PageCacheWarmer\Service\RegenerateUrls
     */
    private $regenerateUrls;

    public function __construct(
        \MageSuite\PageCacheWarmer\Service\RegenerateUrls $regenerateUrls
    )
    {
        parent::__construct();
        $this->regenerateUrls = $regenerateUrls;
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
        $this->regenerateUrls->regenerate();
    }
}


