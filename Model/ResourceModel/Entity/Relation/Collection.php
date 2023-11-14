<?php
namespace MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Relation;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('MageSuite\PageCacheWarmer\Model\Entity\Relation', 'MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Relation');
    }
}
