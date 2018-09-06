<?php
namespace Creativestyle\MageSuite\PageCacheWarmer\Model\Config\Source\UiComponent;

use Creativestyle\MageSuite\PageCacheWarmer\Model\PageCacheWarmer;

class WarmupPriority implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            ['label' => __('No'), 'value' => PageCacheWarmer::NO_WARMUP],
            ['label' => __('Yes, Low priority'), 'value' => PageCacheWarmer::LO_PRIORITY],
            ['label' => __('Yes, High priority - As soon as possible'), 'value' => PageCacheWarmer::HI_PRIORITY]
        ];

        return $options;
    }

}