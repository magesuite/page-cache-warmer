<?php
namespace MageSuite\PageCacheWarmer\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    const ACQUIRE_JOBS_INDEX_COLUMNS = [
        'processing_started_at',
        'priority'
    ];

    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $table = $installer->getConnection()->newTable(
            $installer->getTable('cache_warmup_queue')
        )->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
            'ID'
        )->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'Entity Id'
        )->addColumn(
            'entity_type',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'Entity Type'
        )->addColumn(
            'url',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'Url'
        )->addColumn(
            'customer_group',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => true],
            'Customer Group'
        )->addColumn(
            'priority',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'Priority'
        )->addColumn(
            'processing_started_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            ['nullable' => true],
            'Job processing start date'
        )->addIndex(
            $setup->getIdxName(
                'MageSuite_PageCacheWarmer/cache_warmup_queue',
                self::ACQUIRE_JOBS_INDEX_COLUMNS,
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX
            ),
            self::ACQUIRE_JOBS_INDEX_COLUMNS,
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX]
        );

        $installer->getConnection()->createTable($table);

        $installer->getConnection()->addColumn(
            $installer->getTable('cms_page'),
            'warmup_priority',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'comment' => 'Warmup Priority'
            ]
        );

        $installer->endSetup();
    }
}
