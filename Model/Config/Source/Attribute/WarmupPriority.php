<?php
namespace MageSuite\PageCacheWarmer\Model\Config\Source\Attribute;

use MageSuite\PageCacheWarmer\Model\PageCacheWarmer;

class WarmupPriority extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @var array
     */
    protected $_options = null;

    /**
     * @return array
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = [
                ['label' => __('No'), 'value' => PageCacheWarmer::NO_WARMUP],
                ['label' => __('Yes, Low priority'), 'value' => PageCacheWarmer::LOW_PRIORITY],
                ['label' => __('Yes, High priority - As soon as possible'), 'value' => PageCacheWarmer::HIGH_PRIORITY]
            ];
        }
        return $this->_options;
    }

}