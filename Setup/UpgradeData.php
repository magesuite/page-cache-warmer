<?php
namespace MageSuite\PageCacheWarmer\Setup;

class UpgradeData implements \Magento\Framework\Setup\UpgradeDataInterface
{
    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    protected $eavSetupFactory;

    /**
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface
     */
    protected $moduleDataSetupInterface;

    /**
     * @var \Magento\Eav\Setup\EavSetup
     */
    protected $eavSetup;
    /**
     * @var \Magento\Catalog\Model\Config
     */
    protected $catalogConfig;
    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\Collection
     */
    private $attributeSetCollection;

    public function __construct(
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetupInterface,
        \Magento\Catalog\Model\Config $catalogConfig,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\Collection $attributeSetCollection
    )
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->moduleDataSetupInterface = $moduleDataSetupInterface;
        $this->eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetupInterface]);
        $this->catalogConfig = $catalogConfig;
        $this->attributeSetCollection = $attributeSetCollection;
    }

    public function upgrade(
        \Magento\Framework\Setup\ModuleDataSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.2', '<')) {
            $this->eavSetup->updateAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'warmup_priority',
                'frontend_label',
                'Cache Warm-up'
            );

            $this->eavSetup->updateAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'warmup_priority',
                'note',
                'Page cache for this product can be warmed-up. Select if it shall be warmed up and how important the warm-up is.'
            );

            $this->eavSetup->updateAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                'warmup_priority',
                'frontend_label',
                'Cache Warm-up'
            );

            $this->eavSetup->updateAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                'warmup_priority',
                'note',
                'Page cache for this category can be warmed-up. Select if it shall be warmed up and how important the warm-up is.'
            );
        }

        $setup->endSetup();
    }
}
