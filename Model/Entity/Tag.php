<?php
namespace MageSuite\PageCacheWarmer\Model\Entity;

class Tag extends \Magento\Framework\Model\AbstractModel implements \MageSuite\PageCacheWarmer\Api\Data\Entity\TagInterface
{
    protected function _construct()
    {
        $this->_init('MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Tag');
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

    public function setTag($tag)
    {
        $this->setData('tag', $tag);

        return $this;
    }

    public function getTag()
    {
        return $this->getData('tag');
    }
}