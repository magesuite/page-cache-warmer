<?php
namespace MageSuite\PageCacheWarmer\Test\Service;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class CronSchedulerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \MageSuite\PageCacheWarmer\Service\CronScheduler
     */
    private $cronScheduler;

    /**
     * @var \Magento\Cron\Model\ResourceModel\Schedule\Collection
     */
    private $cronCollection;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->cronScheduler = $this->objectManager->create(\MageSuite\PageCacheWarmer\Service\CronScheduler::class);
        $this->cronCollection = $this->objectManager->create(\Magento\Cron\Model\ResourceModel\Schedule\Collection::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     */
    public function testItScheduleCronCorrectly()
    {
        $this->cronScheduler->schedule();

        $cronCollection = $this->cronCollection;

        $cronCollection
            ->addFieldToFilter('job_code', ['eq' => 'regenerate_page_cache_warmer_urls']);

        $job = $cronCollection->getFirstItem();
        
        $this->assertEquals('regenerate_page_cache_warmer_urls', $job->getJobCode());
    }
}