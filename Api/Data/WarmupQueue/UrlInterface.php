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
     * @return void
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getEntityId();

    /**
     * @param int $id
     * @return void
     */
    public function setEntityId($id);

    /**
     * @return string
     */
    public function getEntityType();

    /**
     * @param string $type
     * @return void
     */
    public function setEntityType($type);

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @param string $url
     * @return void
     */
    public function setUrl($url);

    /**
     * @return string
     */
    public function getCustomerGroup();

    /**
     * @param string $customerGroup
     * @return void
     */
    public function setCustomerGroup($customerGroup);

    /**
     * @return int
     */
    public function getPriority();

    /**
     * @param int $priority
     * @return void
     */
    public function setPriority($priority);
}