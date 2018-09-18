<?php
namespace MageSuite\PageCacheWarmer\Service;

class WarmupEntityCreator
{
    /**
     * @var \Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollectionFactory
     */
    private $urlRewriteCollection;
    /**
     * @var \Magento\Customer\Model\ResourceModel\Group\CollectionFactory
     */
    private $customerGroupCollection;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\PageCacheWarmerFactory
     */
    private $pageCacheWarmerFactory;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\PageCacheWarmerRepository
     */
    private $pageCacheWarmerRepository;
    /**
     * @var \Magento\Store\Api\StoreRepositoryInterface
     */
    private $storeRepository;
    /**
     * @var Configuration
     */
    private $configuration;

    public function __construct(
        \Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollectionFactory $urlRewriteCollection,
        \Magento\Customer\Model\ResourceModel\Group\CollectionFactory $customerGroupCollection,
        \MageSuite\PageCacheWarmer\Model\PageCacheWarmerFactory $pageCacheWarmerFactory,
        \MageSuite\PageCacheWarmer\Model\PageCacheWarmerRepository $pageCacheWarmerRepository,
        \Magento\Store\Api\StoreRepositoryInterface $storeRepository,
        \MageSuite\PageCacheWarmer\Service\Configuration $configuration
    )
    {
        $this->urlRewriteCollection = $urlRewriteCollection;
        $this->customerGroupCollection = $customerGroupCollection;
        $this->pageCacheWarmerFactory = $pageCacheWarmerFactory;
        $this->pageCacheWarmerRepository = $pageCacheWarmerRepository;
        $this->storeRepository = $storeRepository;
        $this->configuration = $configuration;
    }

    public function saveEntity($data)
    {
        foreach ($data as $row) {
            $entity = $this->pageCacheWarmerFactory->create();

            $entity->setData($row);

            $this->pageCacheWarmerRepository->save($entity);
        }
    }

    public function prepareEntity($id, $priority, $entityType)
    {
        $configuration = $this->configuration->getConfiguration();

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

            foreach ($configuration['customer_groups'] as $groupId) {
                $data[$groupId] = $urlData;
                $data[$groupId]['customer_group'] = $groupId;
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