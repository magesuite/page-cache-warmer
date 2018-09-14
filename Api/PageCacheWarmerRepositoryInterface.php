<?php
namespace MageSuite\PageCacheWarmer\Api;

use MageSuite\PageCacheWarmer\Api\Data\PageCacheWarmerInterface;

interface PageCacheWarmerRepositoryInterface
{
    /**
     * @param int $id
     * @return \MageSuite\PageCacheWarmer\Api\Data\PageCacheWarmerInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * @param \MageSuite\PageCacheWarmer\Api\Data\PageCacheWarmerInterface $url
     * @return \MageSuite\PageCacheWarmer\Api\Data\PageCacheWarmerInterface
     */
    public function save(PageCacheWarmerInterface $url);

    /**
     * @param \MageSuite\PageCacheWarmer\Api\Data\PageCacheWarmerInterface $url
     * @return void
     */
    public function delete(PageCacheWarmerInterface $url);
}