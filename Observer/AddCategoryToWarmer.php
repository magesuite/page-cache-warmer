<?php

namespace Creativestyle\MageSuite\PageCacheWarmer\Observer;

use Creativestyle\MageSuite\PageCacheWarmer\Model\PageCacheWarmer;

class AddCategoryToWarmer extends \Creativestyle\MageSuite\PageCacheWarmer\Observer\AbstractWarmerObserver implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->getIsCrawlerEnabled()) {
            return;
        }

        $category = $observer->getCategory();

        if ($category->getWarmupPriority() == PageCacheWarmer::NO_WARMUP) {
            return;
        }
        $warmupEntityCreator = $this->warmupEntityCreator;

        $data = $warmupEntityCreator->prepareEntity($category->getEntityId(), $category->getWarmupPriority(), 'category');

        $warmupEntityCreator->saveEntity($data);
    }
}