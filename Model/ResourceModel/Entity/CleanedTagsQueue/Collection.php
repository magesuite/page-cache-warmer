<?php
namespace MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\CleanedTagsQueue;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('MageSuite\PageCacheWarmer\Model\Entity\CleanedTagsQueue', 'MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\CleanedTagsQueue');
    }
}
