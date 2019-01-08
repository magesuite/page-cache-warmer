<?php
namespace MageSuite\PageCacheWarmer\Observer;

use MageSuite\PageCacheWarmer\Model\WarmupQueue\Url;

class AddCmsToWarmer extends \MageSuite\PageCacheWarmer\Observer\AbstractWarmerObserver implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->getIsCrawlerEnabled()) {
            return;
        }

        $page = $observer->getObject();

        $warmupTags = $page->getIdentities();

        $this->prepareAndSaveEntityTags($page->getPageId(), 'cms-page', $warmupTags);

        if ($page->getWarmupPriority() == Url::NO_WARMUP) {
            return;
        }
       $this->prepareAndSaveEntity($page->getPageId(), $page->getWarmupPriority(), 'cms-page');
    }
}