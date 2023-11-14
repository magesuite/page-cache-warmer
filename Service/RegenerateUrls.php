<?php
namespace MageSuite\PageCacheWarmer\Service;

class RegenerateUrls
{
    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\WarmupQueue\Url\CollectionFactory
     */
    protected $pageWarmerCollectionFactory;
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
    /**
     * @var \Magento\Eav\Api\AttributeRepositoryInterface
     */
    protected $attributeRepository;
    /**
     * @var \Magento\Store\Api\StoreRepositoryInterface
     */
    protected $storeRepository;
    /**
     * @var \MageSuite\PageCacheWarmer\Helper\Configuration
     */
    protected $configuration;
    /**
     * @var \MageSuite\PageCacheWarmer\DataProviders\AdditionalWarmupUrlsInterface
     */
    protected $additionalWarmupUrls;
    /**
     * @var \MageSuite\PageCacheWarmer\Api\UrlRepositoryInterface
     */
    protected $urlRepository;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\WarmupQueue\UrlFactory
     */
    protected $urlFactory;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Url\CollectionFactory
     */
    protected $entityUrlsCollectionFactory;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Relation\CollectionFactory
     */
    protected $entityRelationsCollectionFactory;
    /**
     * @var \Magento\Framework\EntityManager\MetadataPool
     */
    protected $metadataPool;

    public function __construct(
        \MageSuite\PageCacheWarmer\Model\ResourceModel\WarmupQueue\Url\CollectionFactory $pageWarmerCollectionFactory,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Eav\Api\AttributeRepositoryInterface $attributeRepository,
        \Magento\Store\Api\StoreRepositoryInterface $storeRepository,
        \MageSuite\PageCacheWarmer\Helper\Configuration $configuration,
        \MageSuite\PageCacheWarmer\DataProviders\AdditionalWarmupUrlsInterface $additionalWarmupUrls,
        \MageSuite\PageCacheWarmer\Api\UrlRepositoryInterface $urlRepository,
        \MageSuite\PageCacheWarmer\Model\WarmupQueue\UrlFactory $urlFactory,
        \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Url\CollectionFactory $entityUrlsCollectionFactory,
        \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Relation\CollectionFactory $entityRelationsCollectionFactory,
        \Magento\Framework\EntityManager\MetadataPool $metadataPool

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
        $this->entityUrlsCollectionFactory = $entityUrlsCollectionFactory;
        $this->entityRelationsCollectionFactory = $entityRelationsCollectionFactory;
        $this->metadataPool = $metadataPool;
    }

    public function regenerate()
    {
        $this->clearWarmerUrls();

        $configuration = $this->configuration->getConfiguration();

        $this->insertAdditionalUrls($configuration['store_views'], $configuration['customer_groups']);

        foreach ($this->getEntityData() as $data) {
            $data['store_ids'] = $configuration['store_views'];
            $data['customer_groups'] = $configuration['customer_groups'];
            $this->insert($data);
        }
    }

    public function insert($data)
    {
        $table = $data['table'];
        $tableAlias = $data['table_alias'];
        $entityType = $data['entity_type'];
        $linkField = $data['link_field'];

        try {
            if ($entityType == 'cms-page') {
                $priorityExpression = 'cms_page.warmup_priority';
            } else {
                $priorityExpression = 'COALESCE('.$tableAlias. '_store'.'.value, '.$tableAlias. '_default'.'.value)';
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
                    ->where('main_table.entity_type =?', $entityType)
                    ->where('main_table.store_id =?', $storeId);

                if ($entityType != 'cms-page') {
                    $attributeId = $this->attributeRepository->get($data['attribute_entity_type'], 'warmup_priority')->getAttributeId();

                    $tableFieldLink = 'main_table.entity_id';

                    if($linkField == 'row_id'){
                        $select->joinLeft(
                            ['catalog_' . $entityType . '_entity' => 'catalog_' . $entityType . '_entity'],
                            'main_table.entity_id = catalog_' . $entityType . '_entity.entity_id',
                            ['row_id']
                        );

                        $tableFieldLink = 'catalog_' . $entityType . '_entity.row_id';
                    }

                    $select->joinLeft(
                        [$tableAlias . '_default' => $table],
                        $tableFieldLink . ' = ' . $tableAlias. '_default' . '.' .$linkField . ' AND ' . $tableAlias . '_default' . '.attribute_id = ' . $attributeId,
                        ['']
                    );

                    $select->joinLeft(
                        [$tableAlias . '_store' => $table],
                        $tableAlias. '_default' . '.' . $linkField . ' = ' . $tableAlias . '_store' . '.' .$linkField . ' AND ' . $tableAlias . '_default' . '.attribute_id = ' . $tableAlias . '_store' . '.attribute_id AND ' . $tableAlias . '_store' . '.store_id = ' . $storeId,
                        ['']
                    );

                    $select->where($tableAlias. '_default' . '.attribute_id =?', $attributeId);
                    $select->where($tableAlias. '_default' . '.store_id = 0');
                    $select->where('COALESCE('.$tableAlias. '_store'.'.value, '.$tableAlias. '_default'.'.value)');
                } else {
                    $select->joinLeft(
                        [$tableAlias => 'cms_page'],
                        'main_table.entity_id = ' . $tableAlias . '.page_id',
                        ['']
                    )
                        ->where($tableAlias . '.warmup_priority != 0');
                }


                $select->joinLeft(
                        ['customer_group' => 'customer_group'],
                        'main_table.entity_id',
                        ['customer_group' => 'customer_group_id']
                    )
                    ->where('customer_group.customer_group_id IN(?)', $data['customer_groups']);

                $subSelect = $connection->select()
                    ->from(
                        $select,
                        [
                            'entity_type',
                            'entity_id',
                            'url',
                            'priority',
                            'customer_group'
                        ]
                    );

                $insertQuery = $connection->insertFromSelect(
                    $subSelect,
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
                'field' => 'value',
                'link_field' => $this->metadataPool->getMetadata(\Magento\Catalog\Api\Data\CategoryInterface::class)->getLinkField()
            ],
            'product' => [
                'attribute_entity_type' => 'catalog_product',
                'entity_type' => 'product',
                'table' => 'catalog_product_entity_int',
                'table_alias' => 'product_entity',
                'field' => 'value',
                'link_field' => $this->metadataPool->getMetadata(\Magento\Catalog\Api\Data\ProductInterface::class)->getLinkField()
            ],
            'cms' => [
                'entity_type' => 'cms-page',
                'table' => 'cms_page',
                'table_alias' => 'cms_page',
                'field' => 'warmup_priority',
                'link_field' => $this->metadataPool->getMetadata(\Magento\Cms\Api\Data\PageInterface::class)->getLinkField()
            ],
        ];
    }

    public function getStoreBaseUrl($storeId)
    {
        $storeRepository = $this->storeRepository;

        $store = $storeRepository->getById($storeId);

        return $store->getBaseUrl();
    }

    public function clearWarmerUrls()
    {
        $pageWarmerCollection = $this->pageWarmerCollectionFactory->create();
        $entityUrlsCollectionFactory = $this->entityUrlsCollectionFactory->create();
        $entityRelationsCollectionFactory = $this->entityRelationsCollectionFactory->create();

        $connection = $pageWarmerCollection->getConnection();

        $connection->delete($pageWarmerCollection->getMainTable());
        $connection->delete($entityUrlsCollectionFactory->getMainTable());
        $connection->delete($entityRelationsCollectionFactory->getMainTable());
    }
}
