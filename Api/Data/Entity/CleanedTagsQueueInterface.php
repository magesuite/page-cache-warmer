<?php
namespace MageSuite\PageCacheWarmer\Api\Data\Entity;

interface CleanedTagsQueueInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return \MageSuite\PageCacheWarmer\Model\Entity\CleanedTagsQueue
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getTag();

    /**
     * @param string $tag
     * @return \MageSuite\PageCacheWarmer\Model\Entity\CleanedTagsQueue
     */
    public function setTag($tag);
}
