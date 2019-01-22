<?php
namespace MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\TagsCleanupQueue;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('MageSuite\PageCacheWarmer\Model\Entity\TagsCleanupQueue', 'MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\TagsCleanupQueue');
    }
}