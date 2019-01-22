<?php
namespace MageSuite\PageCacheWarmer\Service;

class CleanupQueueCreator
{
    /**
     * @var \MageSuite\PageCacheWarmer\Model\Entity\TagRepository
     */
    protected $tagRepository;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\Entity\TagsCleanupQueue
     */
    protected $tagsCleanupQueue;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\Entity\TagsCleanupQueueRepository
     */
    protected $tagsCleanupQueueRepository;

    public function __construct(
        \MageSuite\PageCacheWarmer\Model\Entity\TagRepository $tagRepository,
        \MageSuite\PageCacheWarmer\Model\Entity\TagsCleanupQueue $tagsCleanupQueue,
        \MageSuite\PageCacheWarmer\Model\Entity\TagsCleanupQueueRepository $tagsCleanupQueueRepository
    )
    {
        $this->tagRepository = $tagRepository;
        $this->tagsCleanupQueue = $tagsCleanupQueue;
        $this->tagsCleanupQueueRepository = $tagsCleanupQueueRepository;
    }

    public function addTagToCleanupQueue($tags)
    {
        foreach ($tags as $tag) {
            $this->addTag($tag);
        }
    }

    public function addTag($tag)
    {
        $tagData = $this->tagRepository->getByTag($tag);

        if (!$tagData) {
            return;
        }

        $cleanupTag = $this->tagsCleanupQueue;

        $cleanupTag->setTag($tagData->getId());

        $this->tagsCleanupQueueRepository->save($cleanupTag);
    }

}