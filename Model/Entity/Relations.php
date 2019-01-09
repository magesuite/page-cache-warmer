<?php
namespace MageSuite\PageCacheWarmer\Model\Entity;

class Relations extends \Magento\Framework\Model\AbstractModel implements \MageSuite\PageCacheWarmer\Api\Data\Entity\RelationsInterface
{
    protected function _construct()
    {
        $this->_init('MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Relations');
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

    public function setUrlId($urlId)
    {
        $this->setData('url_id', $urlId);

        return $this;
    }

    public function getUrlId()
    {
        return $this->getData('url_id');
    }

    public function setTagId($tagId)
    {
        $this->setData('tag_id', $tagId);

        return $this;
    }

    public function getTagId()
    {
        return $this->getData('tag_id');
    }
}