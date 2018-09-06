<?php

namespace Creativestyle\MageSuite\PageCacheWarmer\Observer;

use Creativestyle\MageSuite\PageCacheWarmer\Model\PageCacheWarmer;

class AddCmsToWarmer extends \Creativestyle\MageSuite\PageCacheWarmer\Observer\AbstractWarmerObserver implements \Magento\Framework\Event\ObserverInterface
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

        if ($page->getWarmupPriority() == PageCacheWarmer::NO_WARMUP) {
            return;
        }
        $warmupEntityCreator = $this->warmupEntityCreator;

        $data = $warmupEntityCreator->prepareEntity($page->getPageId(), $page->getWarmupPriority(), 'cms-page');

        $warmupEntityCreator->saveEntity($data);
    }
}