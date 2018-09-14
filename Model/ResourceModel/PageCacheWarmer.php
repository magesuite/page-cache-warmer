<?php
namespace MageSuite\PageCacheWarmer\Model\ResourceModel;

class PageCacheWarmer extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function _construct()
    {
        $this->_init('page_cache_warmer', 'id');
    }
}