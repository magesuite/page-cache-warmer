<?php
namespace MageSuite\PageCacheWarmer\Model\Config\Source;

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

        /**
         * Removed first element to avoid display 'Please Select' placeholder in multiselect
         */
        array_shift($options);

        return $options;
    }
}
