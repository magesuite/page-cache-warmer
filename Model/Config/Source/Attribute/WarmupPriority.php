<?php
namespace MageSuite\PageCacheWarmer\Model\Config\Source\Attribute;

use MageSuite\PageCacheWarmer\Model\WarmupQueue\Url;

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
                ['label' => __('No'), 'value' => Url::NO_WARMUP],
                ['label' => __('Yes, Low priority'), 'value' => Url::LOW_PRIORITY],
                ['label' => __('Yes, High priority - As soon as possible'), 'value' => Url::HIGH_PRIORITY]
            ];
        }
        return $this->_options;
    }

}