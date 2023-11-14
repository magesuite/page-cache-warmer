<?php
namespace MageSuite\PageCacheWarmer\Api;

use MageSuite\PageCacheWarmer\Api\Data\WarmupQueue\UrlInterface;

interface UrlRepositoryInterface
{
    /**
     * @param int $id
     * @return \MageSuite\PageCacheWarmer\Api\Data\WarmupQueue\UrlInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * @param \MageSuite\PageCacheWarmer\Api\Data\WarmupQueue\UrlInterface $url
     * @return \MageSuite\PageCacheWarmer\Api\Data\WarmupQueue\UrlInterface
     */
    public function save(UrlInterface $url);

    /**
     * @param \MageSuite\PageCacheWarmer\Api\Data\WarmupQueue\UrlInterface $url
     * @return void
     */
    public function delete(UrlInterface $url);
}
