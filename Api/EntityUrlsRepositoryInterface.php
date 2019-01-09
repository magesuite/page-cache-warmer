<?php
namespace MageSuite\PageCacheWarmer\Api;

use MageSuite\PageCacheWarmer\Api\Data\Entity\UrlsInterface;

interface EntityUrlsRepositoryInterface
{
    /**
     * @param int $id
     * @return \MageSuite\PageCacheWarmer\Api\Data\Entity\UrlsInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByUrl($id);

    /**
     * @param \MageSuite\PageCacheWarmer\Api\Data\Entity\UrlsInterface $url
     * @return \MageSuite\PageCacheWarmer\Api\Data\Entity\UrlsInterface
     */
    public function save(UrlsInterface $url);

    /**
     * @param \MageSuite\PageCacheWarmer\Api\Data\Entity\UrlsInterface $url
     * @return void
     */
    public function delete(UrlsInterface $url);
}