<?php
namespace MageSuite\PageCacheWarmer\Service;

class CustomerCreator
{
    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    private $customerFactory;
    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private $customerRepository;
    /**
     * @var \Magento\Customer\Model\ResourceModel\Group\CollectionFactory
     */
    private $groupCollectionFactory;
    /**
     * @var \Magento\Customer\Model\ResourceModel\Customer
     */
    private $customerResource;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    public function __construct(
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Model\ResourceModel\Group\CollectionFactory $groupCollectionFactory,
        \Magento\Customer\Model\ResourceModel\Customer $customerResource,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->groupCollectionFactory = $groupCollectionFactory;
        $this->customerResource = $customerResource;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    public function create()
    {
        $customerGroupCollection = $this->groupCollectionFactory->create();

        foreach ($customerGroupCollection as $customerGroup) {
            if($customerGroup->getCustomerGroupId() == 0){
                continue;
            }
            $this->createCustomers($customerGroup);
        }
    }

    public function createCustomers($customerGroup)
    {
        $customers = $this->prepareCustomers($customerGroup);

        foreach ($customers as $customer) {
            $this->customerResource->save($customer);
        }
    }

    public function customerExists($email, $websiteId = null)
    {
        $customer = $this->customerFactory->create();

        if($websiteId) {
            $customer->setWebsiteId($websiteId);
        }

        $customer->loadByEmail($email);

        if($customer->getId()){
            return true;
        }
        return false;
    }

    public function prepareCustomers($customerGroup)
    {
        $config = $this->getConfig();
        $customerData = [
            'firstname' => strtolower($customerGroup->getCustomerGroupCode()),
            'lastname' => strtolower($customerGroup->getCustomerGroupCode()),
            'group_id' => $customerGroup->getCustomerGroupId(),
            'email' => $this->prepareEmail($customerGroup->getCustomerGroupCode()),
            'password' => $this->preparePassword()
        ];

        $email = $customerData['email'];

        $customers = [];
        if ($config['website_scope']) {
            foreach ($this->storeManager->getWebsites() as $website) {
                if($this->customerExists($email, $website->getId())){
                    continue;
                }
                $customerData['website_id'] = $website->getId();
                $customer = $this->customerFactory->create(['data' => $customerData]);
                $customers[] = $customer;
            }
        } else {
            if($this->customerExists($email)){
                return [];
            }
            $customer = $this->customerFactory->create(['data' => $customerData]);
            $customers[] = $customer;
        }

        return $customers;
    }

    public function prepareEmail($customerGroupCode)
    {
        $config = $this->getConfig();

        $localPart = md5($customerGroupCode);
        $domainPart = $config['domain'];

        $domain = '.wu.magesuite.io';

        return $localPart . '@' . $domainPart . $domain;
    }

    public function preparePassword()
    {
        $config = $this->getConfig();

        return $config['password'];
    }

    public function getConfig()
    {
        return [
            'domain' => $this->scopeConfig->getValue('cache_warmer/general/domain'),
            'password' => $this->scopeConfig->getValue('cache_warmer/general/password'),
            'website_scope' => $this->scopeConfig->getValue('customer/account_share/scope') ? true : false
        ];
    }
}