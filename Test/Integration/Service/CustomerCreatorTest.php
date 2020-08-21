<?php
namespace MageSuite\PageCacheWarmer\Test\Service;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class CustomerCreatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \MageSuite\PageCacheWarmer\Service\CustomerCreator
     */
    protected $customerCreatorService;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Group\Collection
     */
    protected $customerGroupCollection;

    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $customer;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->customerCreatorService = $this->objectManager->create(\MageSuite\PageCacheWarmer\Service\CustomerCreator::class);
        $this->customerGroupCollection = $this->objectManager->create(\Magento\Customer\Model\ResourceModel\Group\Collection::class);
        $this->customer = $this->objectManager->create(\Magento\Customer\Model\Customer::class);
        $this->storeManager = $this->objectManager->create(\Magento\Store\Model\StoreManagerInterface::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoConfigFixture default/cache_warmer/general/domain testdomain
     * @magentoConfigFixture default/cache_warmer/general/password test1234
     */
    public function testItPrepareEmailCorrectly()
    {
        $email = $this->customerCreatorService->prepareEmail('Retailer');

        $this->assertEquals('bad0abc5062f0a4c261359eab54bdcc9@testdomain.wu.magesuite.io', $email);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoConfigFixture default/cache_warmer/general/domain testdomain12345
     * @magentoConfigFixture default/cache_warmer/general/password test1234
     */
    public function testItPrepareCustomerDataCorrectly()
    {
        $customerGroupCollection = $this->customerGroupCollection;

        foreach ($customerGroupCollection as $customerGroup) {
            $result = $this->customerCreatorService->prepareCustomers($customerGroup);
            $customer = $result[0];

            $email = $this->customerCreatorService->prepareEmail($customerGroup->getCustomerGroupId());

            $this->assertEquals(strtolower($customerGroup->getCustomerGroupCode()), $customer->getFirstname());
            $this->assertEquals(strtolower($customerGroup->getCustomerGroupCode()), $customer->getLastname());
            $this->assertEquals($customerGroup->getCustomerGroupId(), $customer->getGroupId());
            $this->assertEquals($email, $customer->getEmail());
            $this->assertEquals('test1234', $customer->getPassword());
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
        $validation = $this->customerCreatorService->customerExists('ttt@test.com');

        $this->assertFalse($validation);

        $validation = $this->customerCreatorService->customerExists('customer@example.com');

        $this->assertTrue($validation);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoConfigFixture default/cache_warmer/general/domain testdomain12345
     * @magentoConfigFixture default/cache_warmer/general/password test1234
     * @magentoConfigFixture current_store customer/account_share/scope 0
     */
    public function testItCreateCustomerCorrectly()
    {
        $this->customerCreatorService->create();

        $customerGroupCollection = $this->customerGroupCollection;

        foreach ($customerGroupCollection as $customerGroup) {
            if($customerGroup->getCustomerGroupId() == 0){
                continue;
            }
            $email = $this->customerCreatorService->prepareEmail($customerGroup->getCustomerGroupId());

            $customer = $this->customer->loadByEmail($email);

            $this->assertEquals($customer->getEmail(), $email);
        }
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture websiteFixture
     * @magentoConfigFixture default/cache_warmer/general/domain testdomain12345
     * @magentoConfigFixture default/cache_warmer/general/password test1234
     * @magentoConfigFixture current_store customer/account_share/scope 1
     */
    public function testItCreateCustomerCorrectlyWhenWebsiteScopeEnabled()
    {
        $this->customerCreatorService->create();

        $customerGroupCollection = $this->customerGroupCollection;

        $storeManager = $this->storeManager;

        foreach ($customerGroupCollection as $customerGroup) {
            if($customerGroup->getCustomerGroupId() == 0){
                continue;
            }
            $email = $this->customerCreatorService->prepareEmail($customerGroup->getCustomerGroupId());

            foreach ($storeManager->getWebsites() as $website) {
                $customer = $this->customer;
                $customer->setWebsiteId($website->getId());
                $customer = $customer->loadByEmail($email);

                $this->assertEquals($customer->getEmail(), $email);
            }
        }
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture websiteFixture
     * @magentoConfigFixture default/cache_warmer/general/domain testdomain12345
     * @magentoConfigFixture default/cache_warmer/general/password test1234
     * @magentoAdminConfigFixture customer/address/prefix_show req
     * @magentoAdminConfigFixture customer/address/prefix_options Miss
     */
    public function testItCreateCustomerCorrectlyWhenPrefixIsEnabled()
    {
        $this->customerCreatorService->create();

        $customerGroupCollection = $this->customerGroupCollection;

        $storeManager = $this->storeManager;

        foreach ($customerGroupCollection as $customerGroup) {
            if($customerGroup->getCustomerGroupId() == 0){
                continue;
            }
            $email = $this->customerCreatorService->prepareEmail($customerGroup->getCustomerGroupId());

            foreach ($storeManager->getWebsites() as $website) {
                $customer = $this->customer;
                $customer->setWebsiteId($website->getId());
                $customer = $customer->loadByEmail($email);

                $this->assertEquals($customer->getPrefix(), 'Miss');
            }
        }
    }

    public static function websiteFixture()
    {
        include __DIR__ . '/../_files/website.php';
    }

    public static function websiteFixtureRollback()
    {
        include __DIR__ . '/../_files/website_rollback.php';
    }
}
