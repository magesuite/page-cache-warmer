<?php
namespace MageSuite\PageCacheWarmer\Api;

interface EntityRelationRepositoryInterface
{
    /**
     * @param int $id
     * @return \MageSuite\PageCacheWarmer\Api\Data\Entity\RelationInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * @param \MageSuite\PageCacheWarmer\Api\Data\Entity\RelationInterface $relation
     * @return \MageSuite\PageCacheWarmer\Api\Data\Entity\RelationInterface
     */
    public function save(\MageSuite\PageCacheWarmer\Api\Data\Entity\RelationInterface $relation);

    /**
     * @param \MageSuite\PageCacheWarmer\Api\Data\Entity\RelationInterface $relation
     * @return void
     */
    public function delete(\MageSuite\PageCacheWarmer\Api\Data\Entity\RelationInterface $relation);
}
