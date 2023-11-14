<?php
namespace MageSuite\PageCacheWarmer\Model\ResourceModel\WarmupQueue\Url;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('MageSuite\PageCacheWarmer\Model\WarmupQueue\Url', 'MageSuite\PageCacheWarmer\Model\ResourceModel\WarmupQueue\Url');
    }
}
