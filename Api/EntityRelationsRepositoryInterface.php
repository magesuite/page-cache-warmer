<?php
namespace MageSuite\PageCacheWarmer\Api;

use MageSuite\PageCacheWarmer\Api\Data\Entity\RelationsInterface;

interface EntityRelationsRepositoryInterface
{
    /**
     * @param int $id
     * @return \MageSuite\PageCacheWarmer\Api\Data\Entity\RelationsInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * @param \MageSuite\PageCacheWarmer\Api\Data\Entity\RelationsInterface $relation
     * @return \MageSuite\PageCacheWarmer\Api\Data\Entity\RelationsInterface
     */
    public function save(RelationsInterface $relation);

    /**
     * @param \MageSuite\PageCacheWarmer\Api\Data\Entity\RelationsInterface $relation
     * @return void
     */
    public function delete(RelationsInterface $relation);
}