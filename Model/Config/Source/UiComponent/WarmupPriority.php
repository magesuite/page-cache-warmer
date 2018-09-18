<?php
namespace MageSuite\PageCacheWarmer\Model\Config\Source\UiComponent;

class WarmupPriority implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \MageSuite\PageCacheWarmer\Model\Config\Source\Attribute\WarmupPriority
     */
    private $warmupPriority;

    public function __construct(
        \MageSuite\PageCacheWarmer\Model\Config\Source\Attribute\WarmupPriority $warmupPriority
    )
    {
        $this->warmupPriority = $warmupPriority;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return $this->warmupPriority->getAllOptions();
    }

}