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

        if (version_compare($context->getVersion(), '1.0.3', '<')) {

            $installer->getConnection()->changeColumn(
                $installer->getTable('varnish_cache_relations'),
                'url_id',
                'url_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'length' => 12
                ]
            );

            $installer->getConnection()->changeColumn(
                $installer->getTable('varnish_cache_relations'),
                'tag_id',
                'tag_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'length' => 12
                ]
            );

            $installer->getConnection()->changeColumn(
                $installer->getTable('varnish_cache_url_tags'),
                'entity_type',
                'entity_type',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 50
                ]
            );

            $installer->getConnection()->addIndex(
                $installer->getTable('varnish_cache_relations'),
                $installer->getIdxName(
                    $installer->getTable('varnish_cache_relations'),
                    ['url_id', 'tag_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['url_id', 'tag_id'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
            );

            $installer->getConnection()->changeColumn(
                $installer->getTable('varnish_cache_tags'),
                'tag',
                'tag',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 250
                ]
            );

            $installer->getConnection()->addIndex(
                $installer->getTable('varnish_cache_tags'),
                $installer->getIdxName(
                    $installer->getTable('varnish_cache_tags'),
                    ['tag'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['tag'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
            );

            $installer->getConnection()->changeColumn(
                $installer->getTable('varnish_cache_cleanup_queue'),
                'tag',
                'tag',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 250
                ]
            );

            $installer->getConnection()->addIndex(
                $installer->getTable('varnish_cache_cleanup_queue'),
                $installer->getIdxName(
                    $installer->getTable('varnish_cache_cleanup_queue'),
                    ['tag'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['tag'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
            );
        }

        if (version_compare($context->getVersion(), '1.1.6', '<')) {
            $idxName = $installer->getIdxName(
                $installer->getTable('varnish_cache_url_tags'),
                ['url'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX
            );

            /* The index is added this way, because magento does not have
             * a built-in way of creating a partial (defined length) index. */
            $installer->getConnection()->query(
                sprintf("CREATE INDEX %s ON %s (url(255))",
                    strtoupper($idxName),
                    $installer->getTable('varnish_cache_url_tags')
                )
            );
        }

        $installer->endSetup();
    }
}
