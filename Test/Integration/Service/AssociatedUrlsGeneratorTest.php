<?php
namespace MageSuite\PageCacheWarmer\Test\Service;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class AssociatedUrlsGeneratorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \MageSuite\PageCacheWarmer\Service\AssociatedUrlsGenerator
     */
    protected $associatedUrlsGenerator;

    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Tag\Collection
     */
    protected $tagsCollection;

    /**
     * @var \MageSuite\PageCacheWarmer\Api\EntityTagRepositoryInterface
     */
    protected $tagsRepository;

    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Url\Collection
     */
    protected $urlsCollection;

    /**
     * @var \MageSuite\PageCacheWarmer\Api\EntityUrlRepositoryInterface
     */
    protected $urlsRepository;

    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Relation\CollectionFactory
     */
    protected $relationsCollection;

    /**
     * @var \MageSuite\PageCacheWarmer\Api\EntityRelationRepositoryInterface
     */
    protected $relationsRepository;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->associatedUrlsGenerator = $this->objectManager->create(\MageSuite\PageCacheWarmer\Service\AssociatedUrlsGenerator::class);
        $this->tagsCollection = $this->objectManager->create(\MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Tag\Collection::class);
        $this->tagsRepository = $this->objectManager->create(\MageSuite\PageCacheWarmer\Api\EntityTagRepositoryInterface::class);
        $this->urlsCollection = $this->objectManager->create(\MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Url\Collection::class);
        $this->urlsRepository = $this->objectManager->create(\MageSuite\PageCacheWarmer\Api\EntityUrlRepositoryInterface::class);
        $this->relationsCollection = $this->objectManager->create(\MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Relation\CollectionFactory::class);
        $this->relationsRepository = $this->objectManager->create(\MageSuite\PageCacheWarmer\Api\EntityRelationRepositoryInterface::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     */
    public function testItAddTagsCorrectly()
    {
        $sampleTags = $this->sampleTags();
        $this->associatedUrlsGenerator->addTags(implode(',', $sampleTags));

        $tagsCollection = $this->tagsCollection;

        $this->assertEquals(13, $tagsCollection->getSize());

        foreach ($sampleTags as $tag) {
            $tag = $this->tagsRepository->getByTag($tag);

            $this->assertNotNull($tag);
        }
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     */
    public function testItAddUrlsCorrectly()
    {
        $sampleUrls = $this->sampleUrls();

        foreach ($sampleUrls as $url) {
            $this->associatedUrlsGenerator->addUrls($url['controller'], $url['url']);

            $url = $this->urlsRepository->getByUrl($url['url']);

            $this->assertNotNull($url);
        }
        $this->assertEquals(6, $this->urlsCollection->getSize());
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     */
    public function testItAddRelationsCorrectly()
    {
        $sampleTags = $this->sampleTags();
        $this->associatedUrlsGenerator->addTags(implode(',', $sampleTags));

        foreach ($this->sampleUrls() as $urlData) {
            $this->associatedUrlsGenerator->addUrls($urlData['controller'], $urlData['url']);
            $this->associatedUrlsGenerator->generateRelations(implode(',', $sampleTags), $urlData['url']);
        }

        $this->assertEquals(78, $this->relationsCollection->create()->getSize());
    }

    protected function sampleTags()
    {
        return [
            'cat_p_93',
            'cat_p_234',
            'cat_p_2',
            'cat_p_87',
            'cat_p_328',
            'cat_p_656',
            'cat_p_122',
            'cat_p_34',
            'cat_p_556',
            'cat_p_46',
            'cat_p_889',
            'cat_p_098',
            'cat_p_788',
        ];

    }

    protected function sampleUrls()
    {
        return [
            [
                'controller' => 'index',
                'url' => 'creativeshop.me'
            ],
            [
                'controller' => 'category',
                'url' => 'creativeshop.me/catalog/category/id/2'
            ],
            [
                'controller' => 'category',
                'url' => 'creativeshop.me/catalog/category/id/4'
            ],
            [
                'controller' => 'product',
                'url' => 'creativeshop.me/catalog/product/id/54'
            ],
            [
                'controller' => 'product',
                'url' => 'creativeshop.me/catalog/product/id/4'
            ],
            [
                'controller' => 'page',
                'url' => 'creativeshop.me/about-us'
            ],
        ];
    }
}