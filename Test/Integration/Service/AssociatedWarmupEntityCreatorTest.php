<?php
namespace MageSuite\PageCacheWarmer\Test\Service;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class AssociatedWarmupEntityCreatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \MageSuite\PageCacheWarmer\Service\AssociatedWarmupEntityCreator
     */
    protected $associatedWarmupEntityCreator;

    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\WarmupQueue\Url\Collection
     */
    protected $urlCollection;


    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->associatedWarmupEntityCreator = $this->objectManager->create(\MageSuite\PageCacheWarmer\Service\AssociatedWarmupEntityCreator::class);
        $this->urlCollection = $this->objectManager->create(\MageSuite\PageCacheWarmer\Model\ResourceModel\WarmupQueue\Url\Collection::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture loadRelations
     */
    public function testItAddTagsCorrectly()
    {
        $this->associatedWarmupEntityCreator->addAssociatedUrls(['cat_p_328', 'cat_p_122']);
        foreach ($this->urlCollection as $url) {
            $this->assertTrue(in_array($url->getUrl(), $this->sampleUrls()));
        }
    }

    protected function sampleUrls()
    {
        return [
            'creativeshop.me',
            'creativeshop.me/catalog/category/id/2',
            'creativeshop.me/catalog/category/id/4',
            'creativeshop.me/catalog/product/id/54',
            'creativeshop.me/catalog/product/id/4',
            'creativeshop.me/about-us'
        ];
    }

    public static function loadRelations()
    {
        require __DIR__.'/../_files/associated_urls.php';
    }
}