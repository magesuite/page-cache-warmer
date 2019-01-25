<?php
namespace MageSuite\PageCacheWarmer\Service;

class CleanupQueueCreator
{
    /**
     * @var \MageSuite\PageCacheWarmer\Model\Entity\TagRepository
     */
    protected $tagRepository;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\Entity\CleanedTagsQueueFactory
     */
    protected $cleanedTagsQueueFactory;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\Entity\CleanedTagsQueueRepository
     */
    protected $cleanedTagsQueueRepository;

    public function __construct(
        \MageSuite\PageCacheWarmer\Model\Entity\TagRepository $tagRepository,
        \MageSuite\PageCacheWarmer\Model\Entity\CleanedTagsQueueFactory $cleanedTagsQueueFactory,
        \MageSuite\PageCacheWarmer\Model\Entity\CleanedTagsQueueRepository $cleanedTagsQueueRepository
    )
    {
        $this->tagRepository = $tagRepository;
        $this->cleanedTagsQueueFactory = $cleanedTagsQueueFactory;
        $this->cleanedTagsQueueRepository = $cleanedTagsQueueRepository;
    }

    public function addTagsToCleanupQueue($tags)
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

        $cleanupTag = $this->cleanedTagsQueueFactory->create();

        $cleanupTag->setTag($tagData->getId());

        $this->cleanedTagsQueueRepository->save($cleanupTag);
    }

}