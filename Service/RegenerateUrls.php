<?php
namespace MageSuite\PageCacheWarmer\Service;

class RegenerateUrls
{
    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\WarmupQueue\Url\CollectionFactory
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
     * @var \Magento\Store\Api\StoreRepositoryInterface
     */
    private $storeRepository;
    /**
     * @var \MageSuite\PageCacheWarmer\Helper\Configuration
     */
    private $configuration;
    /**
     * @var \MageSuite\PageCacheWarmer\DataProviders\AdditionalWarmupUrlsInterface
     */
    private $additionalWarmupUrls;
    /**
     * @var \MageSuite\PageCacheWarmer\Api\UrlRepositoryInterface
     */
    private $urlRepository;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\WarmupQueue\UrlFactory
     */
    private $urlFactory;

    public function __construct(
        \MageSuite\PageCacheWarmer\Model\ResourceModel\WarmupQueue\Url\CollectionFactory $pageWarmerCollectionFactory,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Eav\Api\AttributeRepositoryInterface $attributeRepository,
        \Magento\Store\Api\StoreRepositoryInterface $storeRepository,
        \MageSuite\PageCacheWarmer\Helper\Configuration $configuration,
        \MageSuite\PageCacheWarmer\DataProviders\AdditionalWarmupUrlsInterface $additionalWarmupUrls,
        \MageSuite\PageCacheWarmer\Api\UrlRepositoryInterface $urlRepository,
        \MageSuite\PageCacheWarmer\Model\WarmupQueue\UrlFactory $urlFactory

    )
    {
        $this->pageWarmerCollectionFactory = $pageWarmerCollectionFactory;
        $this->resourceConnection = $resourceConnection;
        $this->logger = $logger;
        $this->attributeRepository = $attributeRepository;
        $this->storeRepository = $storeRepository;
        $this->configuration = $configuration;
        $this->additionalWarmupUrls = $additionalWarmupUrls;
        $this->urlRepository = $urlRepository;
        $this->urlFactory = $urlFactory;
    }

    public function regenerate()
    {
        $pageWarmerCollection = $this->pageWarmerCollectionFactory->create();

        $pageWarmerCollection->walk('delete');

        $configuration = $this->configuration->getConfiguration();

        foreach ($this->getEntityData() as $data) {
            $data['store_ids'] = $configuration['store_views'];
            $data['customer_groups'] = $configuration['customer_groups'];
            $this->insert($data);
        }

        $this->insertAdditionalUrls($configuration['store_views'], $configuration['customer_groups']);
    }

    public function insert($data)
    {
        try {
            if ($data['entity_type'] == 'cms-page') {
                $priorityExpression = 'cms_page.warmup_priority';
            } else {
                $priorityExpression = 'COALESCE('.$data['table_alias']. '_store'.'.value, '.$data['table_alias']. '_default'.'.value)';
            }

            foreach ($data['store_ids'] as $storeId) {
                $baseUrl = $this->getStoreBaseUrl($storeId);
                $connection = $this->resourceConnection->getConnection();

                $select = $connection->select()
                    ->from(
                        ['main_table' => 'url_rewrite'],
                        [
                            'entity_type',
                            'entity_id',
                            'url' => new \Zend_Db_Expr("CONCAT('". $baseUrl . "', TRIM(LEADING '/' FROM main_table.request_path))"),
                            'priority' => new \Zend_Db_Expr($priorityExpression)
                        ]
                    )
                    ->where('main_table.entity_type =?', $data['entity_type'])
                    ->where('main_table.store_id =?', $storeId);

                if ($data['entity_type'] != 'cms-page') {
                    $attributeId = $this->attributeRepository->get($data['attribute_entity_type'], 'warmup_priority')->getAttributeId();

                    $select->joinLeft(
                        [$data['table_alias'] . '_default' => $data['table']],
                        'main_table.entity_id = ' . $data['table_alias']. '_default' . '.entity_id AND ' . $data['table_alias'] . '_default' . '.attribute_id = ' . $attributeId,
                        ['']
                    );

                    $select->joinLeft(
                        [$data['table_alias'] . '_store' => $data['table']],
                        $data['table_alias']. '_default' . '.entity_id = ' . $data['table_alias'] . '_store' . '.entity_id AND ' . $data['table_alias'] . '_default' . '.attribute_id = ' . $data['table_alias'] . '_store' . '.attribute_id AND ' . $data['table_alias'] . '_store' . '.store_id = ' . $storeId,
                        ['']
                    );

                    $select->where($data['table_alias']. '_default' . '.attribute_id =?', $attributeId);
                    $select->where($data['table_alias']. '_default' . '.store_id = 0');
                    $select->where('COALESCE('.$data['table_alias']. '_store'.'.value, '.$data['table_alias']. '_default'.'.value)');
                } else {
                    $select->joinLeft(
                        [$data['table_alias'] => 'cms_page'],
                        'main_table.entity_id = ' . $data['table_alias'] . '.page_id',
                        ['']
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
                    'cache_warmup_queue',
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

    public function insertAdditionalUrls($storeViews, $customerGroups)
    {
        try {
            $customUrls = $this->additionalWarmupUrls->getAdditionalUrls();

            foreach ($customUrls as $customUrl) {
                foreach ($storeViews as $storeView) {
                    foreach ($customerGroups as $customerGroup) {
                        $url = $this->urlFactory->create();

                        $baseUrl = $this->getStoreBaseUrl($storeView);

                        $url->setEntityType('custom')
                            ->setUrl($baseUrl . $customUrl)
                            ->setCustomerGroup($customerGroup)
                            ->setPriority(20);

                        $this->urlRepository->save($url);
                    }
                }
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

        return $store->getBaseUrl();
    }
}