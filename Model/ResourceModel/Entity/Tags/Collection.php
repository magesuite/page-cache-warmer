<?php
namespace MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Tags;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('MageSuite\PageCacheWarmer\Model\Entity\Tags', 'MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Tags');
    }
}