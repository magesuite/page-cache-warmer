<?php
namespace MageSuite\PageCacheWarmer\Observer;

class AddCustomerAfterCustomerGroupIsCreated implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \MageSuite\PageCacheWarmer\Helper\Configuration
     */
    private $configuration;
    /**
     * @var \MageSuite\PageCacheWarmer\Service\CustomerCreator
     */
    private $customerCreator;


    public function __construct(
        \MageSuite\PageCacheWarmer\Helper\Configuration $configuration,
        \MageSuite\PageCacheWarmer\Service\CustomerCreator $customerCreator
    )
    {
        $this->configuration = $configuration;
        $this->customerCreator = $customerCreator;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->configuration->isCacheWarmerEnabled()){
            return;
        }

        $this->customerCreator->create();
    }
}