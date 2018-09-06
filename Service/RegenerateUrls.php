<?php
namespace Creativestyle\MageSuite\PageCacheWarmer\Service;

use Zend_Db_Expr;

class RegenerateUrls
{
    /**
     * @var \Creativestyle\MageSuite\PageCacheWarmer\Model\ResourceModel\PageCacheWarmer\CollectionFactory
     */
    private $pageWarmerCollectionFactory;
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    private $resourceConnection;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;
    /**
     * @var \Magento\Eav\Api\AttributeRepositoryInterface
     */
    private $attributeRepository;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var \Magento\Store\Api\StoreRepositoryInterface
     */
    private $storeRepository;

    public function __construct(
        \Creativestyle\MageSuite\PageCacheWarmer\Model\ResourceModel\PageCacheWarmer\CollectionFactory $pageWarmerCollectionFactory,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Eav\Api\AttributeRepositoryInterface $attributeRepository,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Api\StoreRepositoryInterface $storeRepository
    )
    {
        $this->pageWarmerCollectionFactory = $pageWarmerCollectionFactory;
        $this->resourceConnection = $resourceConnection;
        $this->logger = $logger;
        $this->attributeRepository = $attributeRepository;
        $this->scopeConfig = $scopeConfig;
        $this->storeRepository = $storeRepository;
    }

    public function regenerate()
    {
        $pageWarmerCollection = $this->pageWarmerCollectionFactory->create();

        $pageWarmerCollection->walk('delete');

        $storeIds = explode(',', $this->scopeConfig->getValue('cache_warmer/general/store_view'));
        $customerGroups = explode(',', $this->scopeConfig->getValue('cache_warmer/general/customer_group'));

        foreach ($this->getEntityData() as $data) {
            $data['store_ids'] = $storeIds;
            $data['customer_groups'] = $customerGroups;
            $this->insert($data);
        }

    }

    public function insert($data)
    {
        try {
            foreach ($data['store_ids'] as $storeId) {
                $baseUrl = $this->getStoreBaseUrl($storeId);
                $connection = $this->resourceConnection->getConnection();
                $select = $connection->select()
                    ->from(
                        ['main_table' => 'url_rewrite'],
                        [
                            'entity_type',
                            'entity_id',
                            'url' => new Zend_Db_Expr("CONCAT('". $baseUrl . "', main_table.request_path)")
                        ]
                    )
                    ->where('main_table.entity_type =?', $data['entity_type'])
                    ->where('main_table.store_id =?', $storeId);

                if ($data['entity_type'] != 'cms-page') {
                    $attributeId = $this->attributeRepository->get($data['attribute_entity_type'], 'warmup_priority')->getAttributeId();

                    $select->joinLeft(
                        [$data['table_alias'] => $data['table']],
                        'main_table.entity_id = ' . $data['table_alias'] . '.entity_id AND ' . $data['table_alias'] . '.attribute_id = ' . $attributeId,
                        ['priority' => $data['field']]
                    )
                        ->where($data['table_alias'] . '.value != 0');
                } else {
                    $select->joinLeft(
                        [$data['table_alias'] => 'cms_page'],
                        'main_table.entity_id = ' . $data['table_alias'] . '.page_id',
                        ['priority' => 'warmup_priority']
                    )
                        ->where($data['table_alias'] . '.warmup_priority != 0');
                }

                $select->joinLeft(
                        ['customer_group' => 'customer_group'],
                        'main_table.entity_id',
                        ['customer_group' => 'customer_group_id']
                    )
                    ->where('customer_group.customer_group_id IN(?)', $data['customer_groups']);

                $insertQuery = $connection->insertFromSelect(
                    $select,
                    'page_cache_warmer',
                    [
                        'entity_type',
                        'entity_id',
                        'url',
                        'priority',
                        'customer_group'
                    ]
                );

                $connection->query($insertQuery);
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }


    protected function getEntityData()
    {
        return [
            'category' => [
                'attribute_entity_type' => 'catalog_category',
                'entity_type' => 'category',
                'table' => 'catalog_category_entity_int',
                'table_alias' => 'catalog_entity',
                'field' => 'value'
            ],
            'product' => [
                'attribute_entity_type' => 'catalog_product',
                'entity_type' => 'product',
                'table' => 'catalog_product_entity_int',
                'table_alias' => 'product_entity',
                'field' => 'value'
            ],
            'cms' => [
                'entity_type' => 'cms-page',
                'table' => 'cms_page',
                'table_alias' => 'cms_page',
                'field' => 'warmup_priority'
            ],
        ];
    }

    public function getStoreBaseUrl($storeId)
    {
        $storeRepository = $this->storeRepository;

        $store = $storeRepository->getById($storeId);

        $url = $store->getBaseUrl();

        return $url;
    }
}