<?php
namespace MageSuite\PageCacheWarmer\Test\Service;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class CreateCustomersTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \MageSuite\PageCacheWarmer\Service\CreateCustomers
     */
    private $createCustomersService;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Group\Collection
     */
    private $customerGroupCollection;

    /**
     * @var \Magento\Customer\Model\Customer
     */
    private $customer;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->createCustomersService = $this->objectManager->create(\MageSuite\PageCacheWarmer\Service\CreateCustomers::class);
        $this->customerGroupCollection = $this->objectManager->create(\Magento\Customer\Model\ResourceModel\Group\Collection::class);
        $this->customer = $this->objectManager->create(\Magento\Customer\Model\Customer::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoConfigFixture default/cache_warmer/general/domain testdomain
     * @magentoConfigFixture default/cache_warmer/general/password test1234
     * @magentoConfigFixture default/cache_warmer/general/salt 1234567890
     */
    public function testItPrepareEmailCorrectly()
    {
        $email = $this->createCustomersService->prepareEmail('Retailer');

        $this->assertEquals('bad0abc5062f0a4c261359eab54bdcc9@testdomain.wu.magesuite.io', $email);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoConfigFixture default/cache_warmer/general/domain testdomain12345
     * @magentoConfigFixture default/cache_warmer/general/password test1234
     * @magentoConfigFixture default/cache_warmer/general/salt 1234567890
     */
    public function testItPrepareCustomerDataCorrectly()
    {
        $customerGroupCollection = $this->customerGroupCollection;

        foreach ($customerGroupCollection as $customerGroup) {
            $customerData = $this->createCustomersService->prepareCustomer($customerGroup);

            $email = $this->createCustomersService->prepareEmail($customerGroup->getCustomerGroupCode());

            $this->assertEquals(strtolower($customerGroup->getCustomerGroupCode()), $customerData['firstname']);
            $this->assertEquals(strtolower($customerGroup->getCustomerGroupCode()), $customerData['lastname']);
            $this->assertEquals($customerGroup->getCustomerGroupId(), $customerData['group_id']);
            $this->assertEquals($email, $customerData['email']);
            $this->assertEquals('test1234', $customerData['password']);
        }
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Customer/_files/customer.php
     * @magentoConfigFixture current_store customer/account_share/scope 0
     */
    public function testItValidatesCustomerCorrectly()
    {
        $validation = $this->createCustomersService->validateCustomer('ttt@test.com');

        $this->assertTrue($validation);

        $validation = $this->createCustomersService->validateCustomer('customer@example.com');

        $this->assertFalse($validation);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoConfigFixture default/cache_warmer/general/domain testdomain12345
     * @magentoConfigFixture default/cache_warmer/general/password test1234
     * @magentoConfigFixture default/cache_warmer/general/salt 1234567890
     * @magentoConfigFixture current_store customer/account_share/scope 0
     */
    public function testItCreateCustomerCorrectly()
    {
        $this->createCustomersService->create();

        $customerGroupCollection = $this->customerGroupCollection;

        foreach ($customerGroupCollection as $customerGroup) {
            if($customerGroup->getCustomerGroupId() == 0){
                continue;
            }
            $email = $this->createCustomersService->prepareEmail($customerGroup->getCustomerGroupCode());

            $customer = $this->customer->loadByEmail($email);

            $this->assertEquals($customer->getEmail(), $email);
        }
    }
}