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

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->regenerateUrls = $this->objectManager->create(\MageSuite\PageCacheWarmer\Service\RegenerateUrls::class);
        $this->urlCollection = $this->objectManager->create(\MageSuite\PageCacheWarmer\Model\ResourceModel\WarmupQueue\Url\Collection::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoConfigFixture cache_warmer/general/enabled 1
     * @magentoDataFixture loadUrlRewrite
     */
    public function testCmsPrepareEntityDataCorrectly()
    {
        $this->regenerateUrls->regenerate();

        $urlCollection = $this->urlCollection;

        $this->assertEquals(2, $urlCollection->getSize());

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
    }

    public static function loadUrlRewrite()
    {
        require __DIR__.'/../_files/url_rewrite.php';
    }
}