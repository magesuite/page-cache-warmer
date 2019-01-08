<?php
namespace MageSuite\PageCacheWarmer\Service;

class EntityTagsCreator
{
    /**
     * @var \MageSuite\PageCacheWarmer\Api\EntityTagsRepositoryInterface
     */
    private $entityTagsRepository;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\Entity\TagsFactory
     */
    private $entityTagsFactory;

    public function __construct(
        \MageSuite\PageCacheWarmer\Api\EntityTagsRepositoryInterface $entityTagsRepository,
        \MageSuite\PageCacheWarmer\Model\Entity\TagsFactory $entityTagsFactory
    )
    {
        $this->entityTagsRepository = $entityTagsRepository;
        $this->entityTagsFactory = $entityTagsFactory;
    }

    public function saveEntity($data)
    {
        foreach ($data as $row) {
            $entity = $this->entityTagsFactory->create();

            $entity->setData($row);

            $this->entityTagsRepository->save($entity);
        }
    }

    public function prepareEntity($id, $type, $tags)
    {
        $entityData = [];
        foreach ($tags as $tag) {
            $entityData[] = [
                'entity_id' => $id,
                'entity_type' => $type,
                'tag' => $tag
            ];
        }

        return $entityData;
    }

}