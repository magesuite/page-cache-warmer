<?php
namespace MageSuite\PageCacheWarmer\Api;

interface EntityTagsCleanupQueueRepositoryInterface
{
    /**
     * @param int $id
     * @return \MageSuite\PageCacheWarmer\Api\Data\Entity\TagsCleanupQueueInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * @param \MageSuite\PageCacheWarmer\Api\Data\Entity\TagsCleanupQueueInterface $tag
     * @return \MageSuite\PageCacheWarmer\Api\Data\Entity\TagsCleanupQueueInterface
     */
    public function save(\MageSuite\PageCacheWarmer\Api\Data\Entity\TagsCleanupQueueInterface $tag);

    /**
     * @param \MageSuite\PageCacheWarmer\Api\Data\Entity\TagsCleanupQueueInterface $tag
     * @return void
     */
    public function delete(\MageSuite\PageCacheWarmer\Api\Data\Entity\TagsCleanupQueueInterface $tag);
}