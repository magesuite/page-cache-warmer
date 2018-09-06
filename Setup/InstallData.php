<?php

namespace Creativestyle\MageSuite\PageCacheWarmer\Setup;

class InstallData implements \Magento\Framework\Setup\InstallDataInterface
{
    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    private $eavSetupFactory;

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
    private $catalogConfig;
    /**
     * @var \Magento\Eav\Api\AttributeManagementInterface
     */
    private $attributeManagement;

    public function __construct(
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetupInterface,
        \Magento\Catalog\Model\Config $catalogConfig,
        \Magento\Eav\Api\AttributeManagementInterface $attributeManagement
    )
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->moduleDataSetupInterface = $moduleDataSetupInterface;
        $this->eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetupInterface]);
        $this->catalogConfig = $catalogConfig;
        $this->attributeManagement = $attributeManagement;
    }

    public function install(
        \Magento\Framework\Setup\ModuleDataSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '0.0.1', '<')) {

            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'warmup_priority',
                [
                    'type' => 'int',
                    'label' => 'Warmup Priority',
                    'input' => 'select',
                    'group' => 'Cache Warmup',
                    'source' => 'Creativestyle\MageSuite\PageCacheWarmer\Model\Config\Source\Attribute\WarmupPriority',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'class' => '',
                    'visible' => 1,
                    'required' => 0,
                    'user_defined' => 1,
                    'default' => '',
                    'searchable' => 0,
                    'filterable' => 0,
                    'comparable' => 0,
                    'visible_on_front' => 0,
                    'used_in_product_listing' => 0,
                    'unique' => 0,
                    'is_configurable' => 0,
                    'is_visible_in_advanced_search' => 0,
                    'filterable_in_search' => 0,
                    'is_filterable' => 0,

                ]
            );

            $setId = $this->catalogConfig->getAttributeSetId(\Magento\Catalog\Model\Product::ENTITY, 'Default');
            $groupId = $this->catalogConfig->getAttributeGroupId($setId, 'Cache Warmup');

            if (!$groupId) {
                $this->eavSetup->addAttributeGroup(\Magento\Catalog\Model\Product::ENTITY, $setId, 'Cache Warmup', 200);
                $groupId = $this->eavSetup->getAttributeGroupId(\Magento\Catalog\Model\Product::ENTITY, $setId, 'Cache Warmup');
            }

            $this->attributeManagement->assign(
                \Magento\Catalog\Model\Product::ENTITY,
                $setId,
                $groupId,
                'warmup_priority',
                100
            );

            $this->eavSetup->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                'warmup_priority',
                [
                    'type' => 'int',
                    'label' => 'Warmup Priority',
                    'input' => 'select',
                    'visible' => true,
                    'required' => false,
                    'sort_order' => 100,
                    'source' => 'Creativestyle\MageSuite\PageCacheWarmer\Model\Config\Source\Attribute\WarmupPriority',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'group' => 'Cache Warmup',
                ]
            );
        }

        $setup->endSetup();
    }
}
