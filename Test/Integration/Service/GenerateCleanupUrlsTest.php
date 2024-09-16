<?php
namespace MageSuite\PageCacheWarmer\Test\Service;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class GenerateCleanupUrlsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \MageSuite\PageCacheWarmer\Service\CleanedUrlsGenerator
     */
    protected $cleanedUrlsGenerator;

    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\WarmupQueue\Url\Collection
     */
    protected $urlCollection;

    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Tag\Collection
     */
    protected $tagsCollection;

    /**
     * @var \MageSuite\PageCacheWarmer\Service\AssociatedUrlsGenerator
     */
    protected $associatedUrlsGenerator;

    /**
     * @var \MageSuite\PageCacheWarmer\Model\Entity\CleanedTagsQueue
     */
    protected $cleanedTagsQueue;

    /**
     * @var \MageSuite\PageCacheWarmer\Api\EntityCleanedTagsQueueRepositoryInterface
     */
    protected $cleanedTagsQueueRepository;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->urlCollection = $this->objectManager->create(\MageSuite\PageCacheWarmer\Model\ResourceModel\WarmupQueue\Url\Collection::class);
        $this->cleanedUrlsGenerator = $this->objectManager->create(\MageSuite\PageCacheWarmer\Service\CleanedUrlsGenerator::class);
        $this->tagsCollection = $this->objectManager->create(\MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Tag\Collection::class);
        $this->associatedUrlsGenerator = $this->objectManager->create(\MageSuite\PageCacheWarmer\Service\AssociatedUrlsGenerator::class);
        $this->cleanedTagsQueue = $this->objectManager->create(\MageSuite\PageCacheWarmer\Model\Entity\CleanedTagsQueue::class);
        $this->cleanedTagsQueueRepository = $this->objectManager->create(\MageSuite\PageCacheWarmer\Api\EntityCleanedTagsQueueRepositoryInterface::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoConfigFixture cache_warmer/general/enabled 1
     * @magentoConfigFixture cache_warmer/general/customer_group 0,1
     */
    public function testItGenerateCleanupUrlsCorrectly()
    {
        $sampleTags = $this->sampleTags();
        $this->associatedUrlsGenerator->addTags(implode(',', $sampleTags));
        $expectedPages = [];
        $customerGroups = [0,1];

        foreach ($this->sampleUrls() as $urlData) {
            $this->associatedUrlsGenerator->addUrls($urlData['controller'], $urlData['url'], $urlData['entity_id']);
            $this->associatedUrlsGenerator->generateRelations(implode(',', $sampleTags), $urlData['url']);
            $expectedPages = $this->addExpectedPagesForGroups($urlData, $customerGroups, $expectedPages);
        }

        foreach ($this->tagsCollection as $tag) {
            $cleanupTag = $this->cleanedTagsQueue;

            $cleanupTag->setTag($tag->getId());

            $this->cleanedTagsQueueRepository->save($cleanupTag);
        }

        $this->cleanedUrlsGenerator->generate();

        $urlCollection = $this->urlCollection;

        $this->assertEquals(12, $urlCollection->getSize());

        $pages = [];

        foreach ($urlCollection as $page) {
            $pages[] = [
                'id' => $page->getEntityId(),
                'url' => $page->getUrl(),
                'customer_group' => $page->getCustomerGroup()
            ];
        }

        $this->assertContainsEqualsAll($expectedPages, $pages);
    }

    protected function addExpectedPagesForGroups($urlData, $customerGroups, $expectedPages): array
    {
        foreach ($customerGroups as $customerGroup) {
            $expectedPages[] = [
                'id' => $urlData['entity_id']['id'],
                'url' => $urlData['url'],
                'customer_group' => $customerGroup
            ];
        }
        return $expectedPages;
    }

    protected function assertContainsEqualsAll($needles, $haystack): void
    {
        foreach ($needles as $needle) {
            $this->assertContainsEquals($needle, $haystack);
        }
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
                'entity_id' => [
                    'id' => '1'
                ],
                'controller' => 'index',
                'url' => 'creativeshop.me'
            ],
            [
                'entity_id' => [
                    'id' => '2'
                ],
                'controller' => 'category',
                'url' => 'creativeshop.me/catalog/category/id/2'
            ],
            [
                'entity_id' => [
                    'id' => '4'
                ],
                'controller' => 'category',
                'url' => 'creativeshop.me/catalog/category/id/4'
            ],
            [
                'entity_id' => [
                    'id' => '54'
                ],
                'controller' => 'product',
                'url' => 'creativeshop.me/catalog/product/id/54'
            ],
            [
                'entity_id' => [
                    'id' => '4'
                ],
                'controller' => 'product',
                'url' => 'creativeshop.me/catalog/product/id/4'
            ],
            [
                'entity_id' => [
                    'id' => '8'
                ],
                'controller' => 'page',
                'url' => 'creativeshop.me/about-us'
            ],
        ];
    }
}
