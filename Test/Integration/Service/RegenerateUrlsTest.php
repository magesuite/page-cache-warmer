<?php
namespace MageSuite\PageCacheWarmer\Test\Service;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class RegenerateUrlsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \MageSuite\PageCacheWarmer\Service\RegenerateUrls
     */
    private $regenerateUrls;

    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\WarmupQueue\Url\Collection
     */
    private $urlCollection;
    /**
     * @var \MageSuite\PageCacheWarmer\DataProviders\AdditionalWarmupUrlsInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $additionalUrlProvider;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->urlCollection = $this->objectManager->create(\MageSuite\PageCacheWarmer\Model\ResourceModel\WarmupQueue\Url\Collection::class);
        $this->additionalUrlProvider = $this->getMockBuilder(\MageSuite\PageCacheWarmer\DataProviders\AdditionalWarmupUrlsInterface::class)->getMock();
        $this->regenerateUrls = $this->objectManager->create(\MageSuite\PageCacheWarmer\Service\RegenerateUrls::class, ['additionalWarmupUrls' => $this->additionalUrlProvider]);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoConfigFixture cache_warmer/general/enabled 1
     * @magentoDataFixture loadUrlRewrite
     */
    public function testCmsPrepareEntityDataCorrectly()
    {
        $this->additionalUrlProvider->method('getAdditionalUrls')->willReturn(['test_url', 'second_url', 'third_url']);

        $this->regenerateUrls->regenerate();

        $urlCollection = $this->urlCollection;

        $this->assertEquals(8, $urlCollection->getSize());

        $pages = [];
        foreach ($urlCollection as $page) {
            $pages[] = [
                'id' => $page->getEntityId(),
                'url' => $page->getUrl(),
                'priority' => $page->getPriority(),
                'customer_group' => $page->getCustomerGroup()
            ];
        }

        $this->assertEquals(1, $pages[0]['id']);
        $this->assertEquals('http://localhost/index.php/no-route', $pages[0]['url']);
        $this->assertEquals(10, $pages[0]['priority']);
        $this->assertEquals(0, $pages[0]['customer_group']);

        $this->assertEquals(1, $pages[1]['id']);
        $this->assertEquals('http://localhost/index.php/no-route', $pages[1]['url']);
        $this->assertEquals(10, $pages[1]['priority']);
        $this->assertEquals(1, $pages[1]['customer_group']);

        $this->assertEquals(0, $pages[2]['id']);
        $this->assertEquals('http://localhost/index.php/test_url', $pages[2]['url']);
        $this->assertEquals(20, $pages[2]['priority']);
        $this->assertEquals(0, $pages[2]['customer_group']);

        $this->assertEquals(0, $pages[3]['id']);
        $this->assertEquals('http://localhost/index.php/test_url', $pages[3]['url']);
        $this->assertEquals(20, $pages[3]['priority']);
        $this->assertEquals(1, $pages[3]['customer_group']);

        $this->assertEquals(0, $pages[4]['id']);
        $this->assertEquals('http://localhost/index.php/second_url', $pages[4]['url']);
        $this->assertEquals(20, $pages[4]['priority']);
        $this->assertEquals(0, $pages[4]['customer_group']);

        $this->assertEquals(0, $pages[5]['id']);
        $this->assertEquals('http://localhost/index.php/second_url', $pages[5]['url']);
        $this->assertEquals(20, $pages[5]['priority']);
        $this->assertEquals(1, $pages[5]['customer_group']);

        $this->assertEquals(0, $pages[6]['id']);
        $this->assertEquals('http://localhost/index.php/third_url', $pages[6]['url']);
        $this->assertEquals(20, $pages[6]['priority']);
        $this->assertEquals(0, $pages[6]['customer_group']);

        $this->assertEquals(0, $pages[7]['id']);
        $this->assertEquals('http://localhost/index.php/third_url', $pages[7]['url']);
        $this->assertEquals(20, $pages[7]['priority']);
        $this->assertEquals(1, $pages[7]['customer_group']);
    }

    public static function loadUrlRewrite()
    {
        require __DIR__.'/../_files/url_rewrite.php';
    }
}