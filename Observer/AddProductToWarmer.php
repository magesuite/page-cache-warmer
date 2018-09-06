<?php

namespace Creativestyle\MageSuite\PageCacheWarmer\Observer;

use Creativestyle\MageSuite\PageCacheWarmer\Model\PageCacheWarmer;

class AddProductToWarmer extends \Creativestyle\MageSuite\PageCacheWarmer\Observer\AbstractWarmerObserver implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->getIsCrawlerEnabled()) {
            return;
        }

        $product = $observer->getProduct();

        if ($product->getWarmupPriority() == PageCacheWarmer::NO_WARMUP) {
            return;
        }
        $warmupEntityCreator = $this->warmupEntityCreator;

        $data = $warmupEntityCreator->prepareEntity($product->getId(), $product->getWarmupPriority(), 'product');

        $warmupEntityCreator->saveEntity($data);
    }
}