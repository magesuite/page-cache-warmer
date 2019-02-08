<?php
namespace MageSuite\PageCacheWarmer\Setup;

class UpgradeSchema implements \Magento\Framework\Setup\UpgradeSchemaInterface
{
    const ACQUIRE_JOBS_INDEX_COLUMNS = [
        'processing_started_at',
        'priority'
    ];

    public function upgrade(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.0.1', '<')) {

            $table = $installer->getConnection()->newTable(
                $installer->getTable('varnish_cache_tags')
            )->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
                'ID'
            )->addColumn(
                'tag',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Tag'
            );

            $installer->getConnection()->createTable($table);

            $table = $installer->getConnection()->newTable(
                $installer->getTable('varnish_cache_url_tags')
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
            );

            $installer->getConnection()->createTable($table);


            $table = $installer->getConnection()->newTable(
                $installer->getTable('varnish_cache_relations')
            )->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
                'ID'
            )->addColumn(
                'url_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Url Id'
            )->addColumn(
                'tag_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Tag Id'
            );

            $installer->getConnection()->createTable($table);


            $table = $installer->getConnection()->newTable(
                $installer->getTable('varnish_cache_cleanup_queue')
            )->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
                'ID'
            )->addColumn(
                'tag',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Tag Id'
            );

            $installer->getConnection()->createTable($table);

        }

        $installer->endSetup();
    }
}