<?php
namespace MageSuite\PageCacheWarmer\Api;

interface EntityTagRepositoryInterface
{
    /**
     * @param string $tag
     * @return \MageSuite\PageCacheWarmer\Api\Data\Entity\TagInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByTag($tag);

    /**
     * @param \MageSuite\PageCacheWarmer\Api\Data\Entity\TagInterface $tag
     * @return \MageSuite\PageCacheWarmer\Api\Data\Entity\TagInterface
     */
    public function save(\MageSuite\PageCacheWarmer\Api\Data\Entity\TagInterface $tag);

    /**
     * @param \MageSuite\PageCacheWarmer\Api\Data\Entity\TagInterface $tag
     * @return void
     */
    public function delete(\MageSuite\PageCacheWarmer\Api\Data\Entity\TagInterface $tag);
}