<?php
namespace MageSuite\PageCacheWarmer\Service;

class WarmupEntityCreator
{
    /**
     * @var \Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollectionFactory
     */
    protected $urlRewriteCollection;
    /**
     * @var \Magento\Customer\Model\ResourceModel\Group\CollectionFactory
     */
    protected $customerGroupCollection;
    /**
     * @var \Magento\Store\Api\StoreRepositoryInterface
     */
    protected $storeRepository;
    /**
     * @var \MageSuite\PageCacheWarmer\Helper\Configuration
     */
    protected $configuration;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\WarmupQueue\UrlFactory
     */
    protected $urlFactory;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\WarmupQueue\UrlRepository
     */
    protected $urlRepository;

    public function __construct(
        \Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollectionFactory $urlRewriteCollection,
        \Magento\Customer\Model\ResourceModel\Group\CollectionFactory $customerGroupCollection,
        \Magento\Store\Api\StoreRepositoryInterface $storeRepository,
        \MageSuite\PageCacheWarmer\Helper\Configuration $configuration,
        \MageSuite\PageCacheWarmer\Model\WarmupQueue\UrlFactory $urlFactory,
        \MageSuite\PageCacheWarmer\Model\WarmupQueue\UrlRepository $urlRepository
    )
    {
        $this->urlRewriteCollection = $urlRewriteCollection;
        $this->customerGroupCollection = $customerGroupCollection;
        $this->storeRepository = $storeRepository;
        $this->configuration = $configuration;
        $this->urlFactory = $urlFactory;
        $this->urlRepository = $urlRepository;
    }

    public function saveEntity($data)
    {
        foreach ($data as $row) {
            $entity = $this->urlFactory->create();

            $entity->setData($row);

            $this->urlRepository->save($entity);
        }
    }

    public function prepareEntity($id, $priority, $entityType)
    {
        $configuration = $this->configuration->getConfiguration();

        /* Add `null` customer group at all times which always indicates
         * a not logged in user - public cache warming. Public cache should
         * be always warmed even if no other groups are selected so it's added
         * at all times. We use a `null` value so the crawler can easily discern
         * this special case. */
        $customerGroups = array_merge([null], $configuration['customer_groups']);

        $data = [];

        $urlRewriteCollection = $this->urlRewriteCollection->create();

        $urlRewriteCollection
            ->addFieldToFilter('store_id', ['in' => $configuration['store_views']])
            ->addFieldToFilter('entity_id', ['eq' => $id])
            ->addFieldToFilter('entity_type', ['eq' => $entityType]);

        foreach ($urlRewriteCollection as $urlRewrite) {
            $baseUrl = $this->getStoreBaseUrl($urlRewrite->getStoreId());
            $urlData = [
                'entity_id' => $urlRewrite->getEntityId(),
                'entity_type' => $entityType,
                'url' => $baseUrl . $urlRewrite->getRequestPath(),
                'priority' => $priority
            ];

            foreach ($customerGroups as $groupId) {
                $urlData['customer_group'] = $groupId;
                $data[] = $urlData;
            }
        }

        return $data;
    }

    public function getStoreBaseUrl($storeId)
    {
        $storeRepository = $this->storeRepository;

        $store = $storeRepository->getById($storeId);

        return $store->getBaseUrl();
    }
}
