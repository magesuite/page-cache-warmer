<?php
namespace MageSuite\PageCacheWarmer\Service;

class CustomerCreator
{
    const CUSTOMER_EMAIL_HOST_SUFFIX = '.wu.magesuite.io';

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerFactory;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Group\CollectionFactory
     */
    protected $groupCollectionFactory;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Customer
     */
    protected $customerResource;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

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
            if ($customerGroup->getCustomerGroupId() == 0) {
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
            'email' => $this->prepareEmail($customerGroup->getCustomerGroupId()),
            'password' => $this->preparePassword()
        ];

        if($config['prefix_required'] == \Magento\Config\Model\Config\Source\Nooptreq::VALUE_REQUIRED) {
            $customerData['prefix'] = $this->generatePrefix($config);
        }

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

    public function prepareEmail($customerGroupId)
    {
        $domainPrefix = $this->getConfig()['domain'];
        if(!$domainPrefix){
            $domainPrefix = $_SERVER['SERVER_NAME'];
        }
        $hash = md5($customerGroupId);

        return $hash . '@' . $domainPrefix . self::CUSTOMER_EMAIL_HOST_SUFFIX;
    }

    public function preparePassword()
    {
        $password = $this->getConfig()['password'];
        if(!$password){
            $password = "magesuite";
        }
        return $config['password'];
    }

    public function getConfig()
    {
        return [
            'domain' => $this->scopeConfig->getValue('cache_warmer/general/domain'),
            'password' => $this->scopeConfig->getValue('cache_warmer/general/password'),
            'website_scope' => $this->scopeConfig->getValue('customer/account_share/scope') ? true : false,
            'prefix_required' => $this->scopeConfig->getValue('customer/address/prefix_show'),
            'allowed_prefixes' => $this->scopeConfig->getValue('customer/address/prefix_options')
        ];
    }

    protected function generatePrefix(array $config)
    {
        if(empty($config['allowed_prefixes'])) {
            return 'Mr.';
        }

        $allowedPrefixes = explode(';', $config['allowed_prefixes']);

        return $allowedPrefixes[array_rand($allowedPrefixes)];
    }
}