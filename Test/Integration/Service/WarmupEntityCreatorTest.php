<?php
namespace MageSuite\PageCacheWarmer\Test\Service;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class WarmupEntityCreatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \MageSuite\PageCacheWarmer\Service\WarmupEntityCreator
     */
    private $warmupEntityCreator;

    /**
     * @var \Magento\Catalog\Api\CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\PageCacheWarmer\Collection
     */
    private $pageCacheWarmerCollection;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->warmupEntityCreator = $this->objectManager->create(\MageSuite\PageCacheWarmer\Service\WarmupEntityCreator::class);
        $this->categoryRepository = $this->objectManager->create(\Magento\Catalog\Api\CategoryRepositoryInterface::class);
        $this->productRepository = $this->objectManager->create(\Magento\Catalog\Api\ProductRepositoryInterface::class);
        $this->pageCacheWarmerCollection = $this->objectManager->create(\MageSuite\PageCacheWarmer\Model\ResourceModel\PageCacheWarmer\Collection::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     */
    public function testCmsPrepareEntityDataCorrectly()
    {
        $data = $this->warmupEntityCreator->prepareEntity(4, 40, 'cms-page');

        $this->assertEquals($this->getExpectedCmsPrepareEntityData(), $data);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Catalog/_files/category.php
     * @magentoDataFixture loadCategory
     */
    public function testCategoryPrepareEntityDataCorrectly()
    {
        $data = $this->warmupEntityCreator->prepareEntity(333, 10, 'category');

        $this->assertEquals($this->getExpectedCategoryPrepareEntityData(), $data);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Catalog/_files/product_simple.php
     * @magentoDataFixture loadProduct
     */
    public function testProductPrepareEntityDataCorrectly()
    {
        $product = $this->productRepository->get('simple');
        $data = $this->warmupEntityCreator->prepareEntity($product->getId(), 40, 'product');

        $this->assertEquals($this->getExpectedProductPrepareEntityData(), $data);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     */
    public function testItSaveEntityCorrectly()
    {
        $data = $this->getSaveData();
        $this->warmupEntityCreator->saveEntity($data);

        $pageCacheWarmerCollection = $this->pageCacheWarmerCollection;

        $i = 0;
        foreach ($pageCacheWarmerCollection as $row) {
            $this->assertEquals($data[$i]['entity_type'], $row->getData('entity_type'));
            $this->assertEquals($data[$i]['url'], $row->getData('url'));
            $this->assertEquals($data[$i]['priority'], $row->getData('priority'));
            $this->assertEquals($data[$i]['customer_group'], $row->getData('customer_group'));
            $i++;
        }
    }


    public static function loadCategory()
    {
        require __DIR__.'/../_files/category.php';
    }

    public static function loadProduct()
    {
        require __DIR__.'/../_files/product_simple.php';
    }

    protected function getExpectedCmsPrepareEntityData()
    {
        return [
            [
                'entity_id' => '4',
                'entity_type' => 'cms-page',
                'url' => 'http://localhost/index.php/privacy-policy-cookie-restriction-mode',
                'priority' => 40,
                'customer_group' => "0"
            ],
            [
                'entity_id' => '4',
                'entity_type' => 'cms-page',
                'url' => 'http://localhost/index.php/privacy-policy-cookie-restriction-mode',
                'priority' => 40,
                'customer_group' => "1"
            ],
        ];
    }

    protected function getExpectedCategoryPrepareEntityData()
    {
        return [
            [
                'entity_id' => '333',
                'entity_type' => 'category',
                'url' => 'http://localhost/index.php/category-1.html',
                'priority' => 10,
                'customer_group' => "0"
            ],
            [
                'entity_id' => '333',
                'entity_type' => 'category',
                'url' => 'http://localhost/index.php/category-1.html',
                'priority' => 10,
                'customer_group' => "1"
            ],
        ];
    }

    protected function getExpectedProductPrepareEntityData()
    {
        return [
            [
                'entity_id' => '1',
                'entity_type' => 'product',
                'url' => 'http://localhost/index.php/simple-product.html',
                'priority' => 40,
                'customer_group' => "0"
            ],
            [
                'entity_id' => '1',
                'entity_type' => 'product',
                'url' => 'http://localhost/index.php/simple-product.html',
                'priority' => 40,
                'customer_group' => "1"
            ],
        ];
    }

    protected function getSaveData()
    {
        return [
            [
                'entity_id' => '1',
                'entity_type' => 'product',
                'url' => 'http://localhost/index.php/simple-product.html',
                'priority' => 40,
                'customer_group' => "1"
            ],
            [
                'entity_id' => '333',
                'entity_type' => 'category',
                'url' => 'http://localhost/index.php/category-1.html',
                'priority' => 10,
                'customer_group' => "1"
            ],
            [
                'entity_id' => '4',
                'entity_type' => 'cms-page',
                'url' => 'http://localhost/index.php/privacy-policy-cookie-restriction-mode',
                'priority' => 40,
                'customer_group' => "1"
            ],
        ];
    }
}