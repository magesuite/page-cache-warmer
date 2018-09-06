<?php
namespace Creativestyle\MageSuite\PageCacheWarmer\Service;

use function explode;
use function in_array;

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
     * @var \Creativestyle\MageSuite\PageCacheWarmer\Model\PageCacheWarmerFactory
     */
    private $pageCacheWarmerFactory;
    /**
     * @var \Creativestyle\MageSuite\PageCacheWarmer\Model\PageCacheWarmerRepository
     */
    private $pageCacheWarmerRepository;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var \Magento\Store\Api\StoreRepositoryInterface
     */
    private $storeRepository;

    public function __construct(
        \Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollectionFactory $urlRewriteCollection,
        \Magento\Customer\Model\ResourceModel\Group\CollectionFactory $customerGroupCollection,
        \Creativestyle\MageSuite\PageCacheWarmer\Model\PageCacheWarmerFactory $pageCacheWarmerFactory,
        \Creativestyle\MageSuite\PageCacheWarmer\Model\PageCacheWarmerRepository $pageCacheWarmerRepository,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Api\StoreRepositoryInterface $storeRepository
    )
    {
        $this->urlRewriteCollection = $urlRewriteCollection;
        $this->customerGroupCollection = $customerGroupCollection;
        $this->pageCacheWarmerFactory = $pageCacheWarmerFactory;
        $this->pageCacheWarmerRepository = $pageCacheWarmerRepository;
        $this->scopeConfig = $scopeConfig;
        $this->storeRepository = $storeRepository;
    }

    public function saveEntity($data)
    {
        foreach ($data as $row) {
            $entity = $this->pageCacheWarmerFactory->create();

            $entity
                ->setEntityId($row['entity_id'])
                ->setEntityType($row['entity_type'])
                ->setUrl($row['url'])
                ->setPriority($row['priority'])
                ->setCustomerGroup($row['customer_group']);

            $this->pageCacheWarmerRepository->save($entity);
        }
    }

    public function prepareEntity($id, $priority, $entityType)
    {
        $data = [];

        $urlRewriteCollection = $this->urlRewriteCollection->create();

        $urlRewriteCollection
            ->addFieldToFilter('entity_id', ['eq' => $id])
            ->addFieldToFilter('entity_type', ['eq' => $entityType]);

        $customerGroups = explode(',', $this->scopeConfig->getValue('cache_warmer/general/customer_group'));
        $storeViews = explode(',', $this->scopeConfig->getValue('cache_warmer/general/store_view'));
        foreach ($urlRewriteCollection as $urlRewrite) {
            if (!in_array($urlRewrite->getStoreId(), $storeViews)) {
                continue;
            }

            $baseUrl = $this->getStoreBaseUrl($urlRewrite->getStoreId());
            $urlData = [
                'entity_id' => $urlRewrite->getEntityId(),
                'entity_type' => $entityType,
                'url' => $baseUrl . $urlRewrite->getRequestPath(),
                'priority' => $priority
            ];

            foreach ($customerGroups as $groupId) {
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

        $url = $store->getBaseUrl();

        return $url;
    }
}