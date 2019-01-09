<?php
namespace MageSuite\PageCacheWarmer\Api;

use MageSuite\PageCacheWarmer\Api\Data\Entity\TagsInterface;

interface EntityTagsRepositoryInterface
{
    /**
     * @param string $tag
     * @return \MageSuite\PageCacheWarmer\Api\Data\Entity\TagsInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByTag($tag);

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