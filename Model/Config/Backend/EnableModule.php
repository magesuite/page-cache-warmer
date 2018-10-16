<?php
namespace MageSuite\PageCacheWarmer\Model\Config\Backend;

class EnableModule extends \Magento\Framework\App\Config\Value
{
    /**
     * @var \MageSuite\PageCacheWarmer\Service\CreateCustomers
     */
    private $createCustomers;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        \MageSuite\PageCacheWarmer\Service\CreateCustomers $createCustomers,
        array $data = []
    )
    {
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
        $this->createCustomers = $createCustomers;
    }

    /**
     * @return $this
     */
    public function afterSave()
    {
        if ($this->isValueChanged() && $this->getValue() == 1) {
            $this->createCustomers->create();
        }
        return parent::afterSave();
    }
}