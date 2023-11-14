<?php
namespace MageSuite\PageCacheWarmer\Model\ResourceModel\WarmupQueue;

class Url extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function _construct()
    {
        $this->_init('cache_warmup_queue', 'id');
    }
}
