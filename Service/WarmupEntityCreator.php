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
     * @var \Magento\Store\Api\StoreRepositoryInterface
     */
    private $storeRepository;
    /**
     * @var \MageSuite\PageCacheWarmer\Helper\Configuration
     */
    private $configuration;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\WarmupQueue\UrlFactory
     */
    private $urlFactory;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\WarmupQueue\UrlRepository
     */
    private $urlRepository;

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