<?php
namespace MageSuite\PageCacheWarmer\Model\Config\Source;

use function array_shift;

class CustomerGroup implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * @var \Magento\Customer\Model\Config\Source\Group
     */
    private $customerGroupSource;

    public function __construct(
        \Magento\Customer\Model\Config\Source\Group $customerGroupSource
    )
    {
        $this->customerGroupSource = $customerGroupSource;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = $this->customerGroupSource->toOptionArray();

        array_shift($options);

        return $options;
    }
}
