<?php
namespace MageSuite\PageCacheWarmer\Api;

use MageSuite\PageCacheWarmer\Api\Data\Entity\TagsInterface;

interface EntityTagsRepositoryInterface
{
    /**
     * @param int $id
     * @return \MageSuite\PageCacheWarmer\Api\Data\Entity\TagsInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * @param \MageSuite\PageCacheWarmer\Api\Data\Entity\TagsInterface $tag
     * @return \MageSuite\PageCacheWarmer\Api\Data\Entity\TagsInterface
     */
    public function save(TagsInterface $tag);

    /**
     * @param \MageSuite\PageCacheWarmer\Api\Data\Entity\TagsInterface $tag
     * @return void
     */
    public function delete(TagsInterface $tag);
}