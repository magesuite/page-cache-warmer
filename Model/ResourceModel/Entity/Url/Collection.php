<?php
namespace MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Url;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('MageSuite\PageCacheWarmer\Model\Entity\Url', 'MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Url');
    }
}