<?php
namespace MageSuite\PageCacheWarmer\Api\Data\Entity;

interface RelationsInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return \MageSuite\PageCacheWarmer\Model\Entity\Relations
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getUrlId();

    /**
     * @param string $urlId
     * @return \MageSuite\PageCacheWarmer\Model\Entity\Relations
     */
    public function setUrlId($urlId);

    /**
     * @return string
     */
    public function getTagId();

    /**
     * @param string $tagId
     * @return \MageSuite\PageCacheWarmer\Model\Entity\Relations
     */
    public function setTagId($tagId);
}