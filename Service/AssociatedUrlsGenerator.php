<?php
namespace MageSuite\PageCacheWarmer\Service;

class AssociatedUrlsGenerator
{
    protected $entityTypeMap = [
        'product' => 'product',
        'category' => 'category',
        'page' => 'cms-page',
        'index' => 'cms-page'
    ];
    /**
     * @var \MageSuite\PageCacheWarmer\Api\EntityTagRepositoryInterface
     */
    protected $entityTagsRepository;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\Entity\TagsFactory
     */
    protected $tagsFactory;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\Entity\TagRepository
     */
    protected $tagsRepository;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\Entity\UrlsFactory
     */
    protected $urlsFactory;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\Entity\UrlRepository
     */
    protected $urlsRepository;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\Entity\RelationsFactory
     */
    protected $relationsFactory;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\Entity\RelationRepository
     */
    protected $relationsRepository;


    public function __construct(
        \MageSuite\PageCacheWarmer\Api\EntityTagRepositoryInterface $entityTagsRepository,
        \MageSuite\PageCacheWarmer\Model\Entity\TagsFactory $tagsFactory,
        \MageSuite\PageCacheWarmer\Model\Entity\TagRepository $tagsRepository,
        \MageSuite\PageCacheWarmer\Model\Entity\UrlsFactory $urlsFactory,
        \MageSuite\PageCacheWarmer\Model\Entity\UrlRepository $urlsRepository,
        \MageSuite\PageCacheWarmer\Model\Entity\RelationsFactory $relationsFactory,
        \MageSuite\PageCacheWarmer\Model\Entity\RelationRepository $relationsRepository
    )
    {
        $this->entityTagsRepository = $entityTagsRepository;
        $this->tagsFactory = $tagsFactory;
        $this->tagsRepository = $tagsRepository;
        $this->urlsFactory = $urlsFactory;
        $this->urlsRepository = $urlsRepository;
        $this->relationsFactory = $relationsFactory;
        $this->relationsRepository = $relationsRepository;
    }

    public function addAssociatedUrlsToWarmup($value, $controller, $url, $params)
    {
        $this->addTags($value);

        $this->addUrls($controller, $url, $params);

        $this->generateRelations($value, $url);
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

    public function addUrls($controller, $url, $params)
    {
        $entityId = array_shift($params);
        $entityType = $this->getEntityType($controller);

        $urlEntity = $this->urlsFactory->create();
        $urlEntity->setEntityId($entityId)
            ->setEntityType($entityType)
            ->setUrl($url);

        $this->urlsRepository->save($urlEntity);
    }

    public function generateRelations($tags, $url)
    {
        $tags = explode(',', $tags);

        $urlData = $this->urlsRepository->getByUrl($url);

        foreach ($tags as $tag) {
            $tagData = $this->tagsRepository->getByTag($tag);

            $relation = $this->relationsFactory->create();

            $relation->setTagId($tagData->getId())
                ->setUrlId($urlData->getId());

            $this->relationsRepository->save($relation);
        }
    }

    protected function getEntityType($controllerName)
    {
        $entityTypeMap = $this->entityTypeMap;

        return $entityTypeMap[$controllerName];
    }
}