<?php
namespace MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Urls;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('MageSuite\PageCacheWarmer\Model\Entity\Urls', 'MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Urls');
    }
}