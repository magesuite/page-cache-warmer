<?php
namespace MageSuite\PageCacheWarmer\Api\Data\Entity;

interface UrlInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return \MageSuite\PageCacheWarmer\Model\Entity\Url
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getEntityId();

    /**
     * @param int $id
     * @return \MageSuite\PageCacheWarmer\Model\Entity\Url
     */
    public function setEntityId($id);

    /**
     * @return string
     */
    public function getEntityType();

    /**
     * @param string $type
     * @return \MageSuite\PageCacheWarmer\Model\Entity\Url
     */
    public function setEntityType($type);

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @param string $url
     * @return \MageSuite\PageCacheWarmer\Model\Entity\Url
     */
    public function setUrl($url);
}
