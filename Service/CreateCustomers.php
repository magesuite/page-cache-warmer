<?php
namespace MageSuite\PageCacheWarmer\Service;


class CreateCustomers
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

    public function __construct(
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Model\ResourceModel\Group\CollectionFactory $groupCollectionFactory,
        \Magento\Customer\Model\ResourceModel\Customer $customerResource,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->groupCollectionFactory = $groupCollectionFactory;
        $this->customerResource = $customerResource;
        $this->scopeConfig = $scopeConfig;
    }

    public function create()
    {
        $customerGroupCollection = $this->groupCollectionFactory->create();

        foreach ($customerGroupCollection as $customerGroup) {
            if($customerGroup->getCustomerGroupId() == 0){
                continue;
            }
            $this->createCustomer($customerGroup);
        }
    }

    public function createCustomer($customerGroup)
    {
        $email = $this->prepareEmail($customerGroup->getCustomerGroupCode());

        if(!$this->validateCustomer($email)){
            return;
        }

        $customer = $this->customerFactory->create();

        $customerData = $this->prepareCustomer($customerGroup);

        $customer->setData($customerData);

        $this->customerResource->save($customer);
    }

    public function validateCustomer($email)
    {
        $customer = $this->customerFactory->create();

        $customer->loadByEmail($email);

        if($customer->getId()){
            return false;
        }
        return true;
    }

    public function prepareCustomer($customerGroup)
    {
        return [
            'firstname' => strtolower($customerGroup->getCustomerGroupCode()),
            'lastname' => strtolower($customerGroup->getCustomerGroupCode()),
            'group_id' => $customerGroup->getCustomerGroupId(),
            'email' => $this->prepareEmail($customerGroup->getCustomerGroupCode()),
            'password' => $this->preparePassword()
        ];
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
            'salt' => $this->scopeConfig->getValue('cache_warmer/general/salt'),
        ];
    }
}