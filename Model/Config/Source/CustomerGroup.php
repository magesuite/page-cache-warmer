<?php
namespace Creativestyle\MageSuite\PageCacheWarmer\Model\Config\Source;

class CustomerGroup implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Magento\Customer\Model\ResourceModel\Group\Collection
     */
    private $customerGroupCollection;

    public function __construct(
        \Magento\Customer\Model\ResourceModel\Group\Collection $customerGroupCollection
    )
    {
        $this->customerGroupCollection = $customerGroupCollection;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        $customerGroups = $this->customerGroupCollection;
        foreach ($customerGroups as $customerGroup) {
            $options[] = [
                'label' => $customerGroup->getCustomerGroupCode(),
                'value' => $customerGroup->getCustomerGroupId()
            ];
        }

        return $options;
    }
}
