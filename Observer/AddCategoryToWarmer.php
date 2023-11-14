<?php
namespace MageSuite\PageCacheWarmer\Observer;

use MageSuite\PageCacheWarmer\Model\WarmupQueue\Url;

class AddCategoryToWarmer extends \MageSuite\PageCacheWarmer\Observer\AbstractWarmerObserver implements \Magento\Framework\Event\ObserverInterface
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


        if ($category->getWarmupPriority() == Url::NO_WARMUP) {
            return;
        }
        $warmupTags = $category->getIdentities();

        $this->prepareAndSaveEntityAssociatedUrls($warmupTags);

        $this->prepareAndSaveEntity($category->getEntityId(), $category->getWarmupPriority(), 'category');
    }
}
