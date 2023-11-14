<?php
namespace MageSuite\PageCacheWarmer\Plugin;

class AddTagsToCleanedQueue
{
    /**
     * @var \MageSuite\PageCacheWarmer\Service\CleanedTagsQueueManager
     */
    protected $cleanedTagsQueueManager;

    public function __construct(\MageSuite\PageCacheWarmer\Service\CleanedTagsQueueManager $cleanedTagsQueueManager)
    {
        $this->cleanedTagsQueueManager = $cleanedTagsQueueManager;
    }

    public function aroundClean(\Magento\Framework\App\Cache $subject, callable $proceed, $tags = [])
    {
        if (!empty($tags)) {
            $this->cleanedTagsQueueManager->addTagsToCleanupQueue($tags);
        }

        return $proceed($tags);
    }
}
