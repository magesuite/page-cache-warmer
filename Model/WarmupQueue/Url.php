<?php
namespace MageSuite\PageCacheWarmer\Model\WarmupQueue;

class Url extends \Magento\Framework\Model\AbstractModel implements \MageSuite\PageCacheWarmer\Api\Data\WarmupQueue\UrlInterface
{
    const NO_WARMUP = 0;
    const LOW_PRIORITY = 10;
    const HIGH_PRIORITY = 20;

    protected function _construct()
    {
        $this->_init('MageSuite\PageCacheWarmer\Model\ResourceModel\WarmupQueue\Url');
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

    public function setCustomerGroup($customerGroup)
    {
        $this->setData('customer_group', $customerGroup);

        return $this;
    }

    public function getCustomerGroup()
    {
        return $this->getData('customer_group');
    }

    public function setPriority($priority)
    {
        $this->setData('priority', $priority);

        return $this;
    }

    public function getPriority()
    {
        return $this->getData('priority');
    }
}