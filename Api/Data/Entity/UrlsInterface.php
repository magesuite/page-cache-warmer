<?php
namespace MageSuite\PageCacheWarmer\Api\Data\Entity;

interface UrlsInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return \MageSuite\PageCacheWarmer\Model\Entity\Urls
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getEntityId();

    /**
     * @param int $id
     * @return \MageSuite\PageCacheWarmer\Model\Entity\Urls
     */
    public function setEntityId($id);

    /**
     * @return string
     */
    public function getEntityType();

    /**
     * @param string $type
     * @return \MageSuite\PageCacheWarmer\Model\Entity\Urls
     */
    public function setEntityType($type);

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @param string $url
     * @return \MageSuite\PageCacheWarmer\Model\Entity\Urls
     */
    public function setUrl($url);
}