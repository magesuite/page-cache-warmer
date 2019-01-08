<?php
namespace MageSuite\PageCacheWarmer\Service;

class AssociatedUrlsGenerator
{
    /**
     * @var \MageSuite\PageCacheWarmer\Api\EntityTagsRepositoryInterface
     */
    private $entityTagsRepository;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\Entity\TagsFactory
     */
    private $tagsFactory;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\Entity\TagsRepository
     */
    private $tagsRepository;

    public function __construct(
        \MageSuite\PageCacheWarmer\Api\EntityTagsRepositoryInterface $entityTagsRepository,
        \MageSuite\PageCacheWarmer\Model\Entity\TagsFactory $tagsFactory,
        \MageSuite\PageCacheWarmer\Model\Entity\TagsRepository $tagsRepository
    )
    {
        $this->entityTagsRepository = $entityTagsRepository;
        $this->tagsFactory = $tagsFactory;
        $this->tagsRepository = $tagsRepository;
    }

    public function addAssociatedUrlsToWarmup($value, $controller, $url, $params)
    {
        $this->addTags($value);
    }

    public function addTags($tags)
    {
        $tags = explode(',', $tags);

        foreach ($tags as $tag) {
            $tagEntity = $this->tagsFactory->create();
            $tagEntity->setTag($tag);

            $this->tagsRepository->save($tagEntity);
        }
    }
}