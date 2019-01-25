<?php
namespace MageSuite\PageCacheWarmer\Model\ResourceModel\Entity;

class CleanedTagsQueue extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function _construct()
    {
        $this->_init('varnish_cache_cleanup_queue', 'id');
    }
}