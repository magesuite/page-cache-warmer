<?php
namespace Creativestyle\MageSuite\PageCacheWarmer\Model\ResourceModel\PageCacheWarmer;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Creativestyle\MageSuite\PageCacheWarmer\Model\PageCacheWarmer', 'Creativestyle\MageSuite\PageCacheWarmer\Model\ResourceModel\PageCacheWarmer');
    }
}