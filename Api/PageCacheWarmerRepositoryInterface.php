<?php
namespace Creativestyle\MageSuite\PageCacheWarmer\Api;

use Creativestyle\MageSuite\PageCacheWarmer\Api\Data\PageCacheWarmerInterface;

interface PageCacheWarmerRepositoryInterface
{
    /**
     * @param int $id
     * @return \Creativestyle\MageSuite\PageCacheWarmer\Api\Data\PageCacheWarmerInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * @param \Creativestyle\MageSuite\PageCacheWarmer\Api\Data\PageCacheWarmerInterface $url
     * @return \Creativestyle\MageSuite\PageCacheWarmer\Api\Data\PageCacheWarmerInterface
     */
    public function save(PageCacheWarmerInterface $url);

    /**
     * @param \Creativestyle\MageSuite\PageCacheWarmer\Api\Data\PageCacheWarmerInterface $url
     * @return void
     */
    public function delete(PageCacheWarmerInterface $url);
}