<?php
namespace MageSuite\PageCacheWarmer\Observer;

use MageSuite\PageCacheWarmer\Model\WarmupQueue\Url;

class AddProductToWarmer extends \MageSuite\PageCacheWarmer\Observer\AbstractWarmerObserver implements \Magento\Framework\Event\ObserverInterface
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

        $warmupTags = $product->getIdentities();

        $this->prepareAndSaveEntityTags($product->getId(), 'product', $warmupTags);

        if ($product->getWarmupPriority() == Url::NO_WARMUP) {
            return;
        }
        $this->prepareAndSaveEntity($product->getId(), $product->getWarmupPriority(), 'product');
    }
}