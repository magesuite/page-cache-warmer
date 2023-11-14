<?php
namespace MageSuite\PageCacheWarmer\Api\Data\Entity;

interface TagInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return \MageSuite\PageCacheWarmer\Model\Entity\Tag
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getTag();

    /**
     * @param string $tag
     * @return \MageSuite\PageCacheWarmer\Model\Entity\Tag
     */
    public function setTag($tag);
}
