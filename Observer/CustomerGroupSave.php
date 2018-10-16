<?php
namespace MageSuite\PageCacheWarmer\Observer;

class CustomerGroupSave implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \MageSuite\PageCacheWarmer\Helper\Configuration
     */
    private $configuration;
    /**
     * @var \MageSuite\PageCacheWarmer\Service\CreateCustomers
     */
    private $createCustomers;

    public function __construct(
        \MageSuite\PageCacheWarmer\Helper\Configuration $configuration,
        \MageSuite\PageCacheWarmer\Service\CreateCustomers $createCustomers
    )
    {
        $this->configuration = $configuration;
        $this->createCustomers = $createCustomers;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->configuration->isCacheWarmerEnabled()){
            return;
        }

        $this->createCustomers->create();
    }
}