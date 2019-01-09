<?php
namespace MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Relations;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('MageSuite\PageCacheWarmer\Model\Entity\Relations', 'MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Relations');
    }
}