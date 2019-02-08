<?php
namespace MageSuite\PageCacheWarmer\Model\Config\Source;

class StoreView implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Magento\Store\Model\ResourceModel\Store\Collection
     */
    protected $storeCollection;

    public function __construct(
        \Magento\Store\Model\ResourceModel\Store\Collection $storeCollection
    )
    {
        $this->storeCollection = $storeCollection;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        $storesCollection = $this->storeCollection;
        foreach ($storesCollection as $store) {
            $options[] = [
                'label' => $store->getName(),
                'value' => $store->getStoreId()
            ];

        }

        return $options;

    }
}
