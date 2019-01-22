<?php
namespace MageSuite\PageCacheWarmer\Model\Entity;

class TagsCleanupQueue extends \Magento\Framework\Model\AbstractModel implements \MageSuite\PageCacheWarmer\Api\Data\Entity\TagsCleanupQueueInterface
{
    protected function _construct()
    {
        $this->_init('MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\TagsCleanupQueue');
    }

    public function setId($id)
    {
        $this->setData('id', $id);

        return $this;
    }

    public function getId()
    {
        return $this->getData('id');
    }

    public function setTag($tagId)
    {
        $this->setData('tag', $tagId);

        return $this;
    }

    public function getTag()
    {
        return $this->getData('tag');
    }
}