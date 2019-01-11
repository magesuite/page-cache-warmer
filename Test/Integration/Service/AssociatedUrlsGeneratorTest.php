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
    private $objectManager;

    /**
     * @var \MageSuite\PageCacheWarmer\Service\AssociatedUrlsGenerator
     */
    private $associatedUrlsGenerator;

    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Tags\Collection
     */
    private $tagsCollection;

    /**
     * @var \MageSuite\PageCacheWarmer\Api\EntityTagsRepositoryInterface
     */
    private $tagsRepository;

    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Urls\Collection
     */
    private $urlsCollection;

    /**
     * @var \MageSuite\PageCacheWarmer\Api\EntityUrlsRepositoryInterface
     */
    private $urlsRepository;

    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Relations\Collection
     */
    private $relationsCollection;

    /**
     * @var \MageSuite\PageCacheWarmer\Api\EntityRelationsRepositoryInterface
     */
    private $relationsRepository;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->associatedUrlsGenerator = $this->objectManager->create(\MageSuite\PageCacheWarmer\Service\AssociatedUrlsGenerator::class);
        $this->tagsCollection = $this->objectManager->create(\MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Tags\Collection::class);
        $this->tagsRepository = $this->objectManager->create(\MageSuite\PageCacheWarmer\Api\EntityTagsRepositoryInterface::class);
        $this->urlsCollection = $this->objectManager->create(\MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Urls\Collection::class);
        $this->urlsRepository = $this->objectManager->create(\MageSuite\PageCacheWarmer\Api\EntityUrlsRepositoryInterface::class);
        $this->relationsCollection = $this->objectManager->create(\MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Relations\Collection::class);
        $this->relationsRepository = $this->objectManager->create(\MageSuite\PageCacheWarmer\Api\EntityRelationsRepositoryInterface::class);
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

    public function testItAddUrlsCorrectly()
    {
        $sampleUrls = $this->sampleUrls();

        foreach ($sampleUrls as $url) {
            $this->associatedUrlsGenerator->addUrls($url['controller'], $url['url'], [$url['entity_id']]);

            $url = $this->urlsRepository->getByUrl($url['url']);

            $this->assertNotNull($url);
        }
        $this->assertEquals(6, $this->urlsCollection->getSize());
    }

    public function testItAddRelationsCorrectly()
    {
        foreach ($this->sampleUrls() as $urlData) {
            $this->associatedUrlsGenerator->generateRelations(implode(',', $this->sampleTags()), $urlData['url']);
        }

        $this->assertEquals(78, $this->relationsCollection->getSize());
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
                'entity_id' => '1',
                'controller' => 'index',
                'url' => 'creativeshop.me'
            ],
            [
                'entity_id' => '2',
                'controller' => 'category',
                'url' => 'creativeshop.me/catalog/category/id/2'
            ],
            [
                'entity_id' => '4',
                'controller' => 'category',
                'url' => 'creativeshop.me/catalog/category/id/4'
            ],
            [
                'entity_id' => '54',
                'controller' => 'product',
                'url' => 'creativeshop.me/catalog/product/id/54'
            ],
            [
                'entity_id' => '4',
                'controller' => 'product',
                'url' => 'creativeshop.me/catalog/product/id/4'
            ],
            [
                'entity_id' => '8',
                'controller' => 'page',
                'url' => 'creativeshop.me/about-us'
            ],
        ];
    }
}