<?php
namespace MageSuite\PageCacheWarmer\Model\ResourceModel\PageCacheWarmer;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('MageSuite\PageCacheWarmer\Model\PageCacheWarmer', 'MageSuite\PageCacheWarmer\Model\ResourceModel\PageCacheWarmer');
    }
}