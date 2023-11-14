<?php
namespace MageSuite\PageCacheWarmer\Api\Data\WarmupQueue;

interface UrlInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return \MageSuite\PageCacheWarmer\Model\WarmupQueue\Url
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getEntityId();

    /**
     * @param int $id
     * @return \MageSuite\PageCacheWarmer\Model\WarmupQueue\Url
     */
    public function setEntityId($id);

    /**
     * @return string
     */
    public function getEntityType();

    /**
     * @param string $type
     * @return \MageSuite\PageCacheWarmer\Model\WarmupQueue\Url
     */
    public function setEntityType($type);

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @param string $url
     * @return \MageSuite\PageCacheWarmer\Model\WarmupQueue\Url
     */
    public function setUrl($url);

    /**
     * @return string
     */
    public function getCustomerGroup();

    /**
     * @param string $customerGroup
     * @return \MageSuite\PageCacheWarmer\Model\WarmupQueue\Url
     */
    public function setCustomerGroup($customerGroup);

    /**
     * @return int
     */
    public function getPriority();

    /**
     * @param int $priority
     * @return \MageSuite\PageCacheWarmer\Model\WarmupQueue\Url
     */
    public function setPriority($priority);
}
