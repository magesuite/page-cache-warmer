<?php
namespace MageSuite\PageCacheWarmer\Api;

use MageSuite\PageCacheWarmer\Api\Data\Entity\UrlInterface;

interface EntityUrlRepositoryInterface
{
    /**
     * @param int $id
     * @return \MageSuite\PageCacheWarmer\Api\Data\Entity\UrlInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByUrl($id);

    /**
     * @param \MageSuite\PageCacheWarmer\Api\Data\Entity\UrlInterface $url
     * @return \MageSuite\PageCacheWarmer\Api\Data\Entity\UrlInterface
     */
    public function save(\MageSuite\PageCacheWarmer\Api\Data\Entity\UrlInterface $url);

    /**
     * @param \MageSuite\PageCacheWarmer\Api\Data\Entity\UrlInterface $url
     * @return void
     */
    public function delete(\MageSuite\PageCacheWarmer\Api\Data\Entity\UrlInterface $url);
}