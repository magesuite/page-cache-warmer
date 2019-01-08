<?php
namespace MageSuite\PageCacheWarmer\Model\Entity;

class Urls extends \Magento\Framework\Model\AbstractModel implements \MageSuite\PageCacheWarmer\Api\Data\Entity\TagsInterface
{
    protected function _construct()
    {
        $this->_init('MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Urls');
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

    public function setEntityId($id)
    {
        $this->setData('entity_id', $id);

        return $this;
    }

    public function getEntityId()
    {
        return $this->getData('entity_id');
    }

    public function setEntityType($type)
    {
        $this->setData('entity_type', $type);

        return $this;
    }

    public function getEntityType()
    {
        return $this->getData('entity_type');
    }

    public function setUrl($url)
    {
        $this->setData('url', $url);

        return $this;
    }

    public function getUrl()
    {
        return $this->getData('url');
    }
}