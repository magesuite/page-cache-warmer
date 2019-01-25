<?php
namespace MageSuite\PageCacheWarmer\Plugin;

class AddTagsToCleanupQueue
{
    /**
     * @var \MageSuite\PageCacheWarmer\Service\CleanupQueueCreator
     */
    protected $cleanupQueueCreator;

    public function __construct(\MageSuite\PageCacheWarmer\Service\CleanupQueueCreator $cleanupQueueCreator)
    {
        $this->cleanupQueueCreator = $cleanupQueueCreator;
    }

    public function aroundClean(\Magento\Framework\App\Cache $subject, callable $proceed, $tags = [])
    {
        if (!empty($tags)) {
            $this->cleanupQueueCreator->addTagsToCleanupQueue($tags);
        }

        return $proceed($tags);
    }
}