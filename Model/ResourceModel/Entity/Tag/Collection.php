<?php
namespace MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Tag;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('MageSuite\PageCacheWarmer\Model\Entity\Tag', 'MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Tag');
    }
}
