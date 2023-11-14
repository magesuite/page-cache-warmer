<?php
namespace MageSuite\PageCacheWarmer\Api;

interface EntityCleanedTagsQueueRepositoryInterface
{
    /**
     * @param int $id
     * @return \MageSuite\PageCacheWarmer\Api\Data\Entity\CleanedTagsQueueInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * @param \MageSuite\PageCacheWarmer\Api\Data\Entity\CleanedTagsQueueInterface $tag
     * @return \MageSuite\PageCacheWarmer\Api\Data\Entity\CleanedTagsQueueInterface
     */
    public function save(\MageSuite\PageCacheWarmer\Api\Data\Entity\CleanedTagsQueueInterface $tag);

    /**
     * @param \MageSuite\PageCacheWarmer\Api\Data\Entity\CleanedTagsQueueInterface $tag
     * @return void
     */
    public function delete(\MageSuite\PageCacheWarmer\Api\Data\Entity\CleanedTagsQueueInterface $tag);
}
