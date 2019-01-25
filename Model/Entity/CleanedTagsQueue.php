<?php
namespace MageSuite\PageCacheWarmer\Model\Entity;

class CleanedTagsQueue extends \Magento\Framework\Model\AbstractModel implements \MageSuite\PageCacheWarmer\Api\Data\Entity\CleanedTagsQueueInterface
{
    protected function _construct()
    {
        $this->_init('MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\CleanedTagsQueue');
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