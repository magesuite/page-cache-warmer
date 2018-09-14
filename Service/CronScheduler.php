<?php
/**
 * Created by PhpStorm.
 * User: blazejdoleska
 * Date: 07/09/2018
 * Time: 14:06
 */

namespace MageSuite\PageCacheWarmer\Service;


class CronScheduler
{
    const JOB_NAME = 'regenerate_page_cache_warmer_urls';
    /**
     * @var \Magento\Cron\Model\Schedule
     */
    private $schedule;
    /**
     * @var \Magento\Cron\Model\ResourceModel\Schedule
     */
    private $scheduleResource;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(
        \Magento\Cron\Model\Schedule $schedule,
        \Magento\Cron\Model\ResourceModel\Schedule $scheduleResource,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->schedule = $schedule;
        $this->scheduleResource = $scheduleResource;
        $this->logger = $logger;
    }

    public function schedule()
    {
        try {
            $cronSchedule = $this->schedule;
            $cronSchedule
                ->setJobCode(self::JOB_NAME)
                ->setCreatedAt(date("Y-m-d H:i:s"))
                ->setScheduledAt(date('Y-m-d H:i:s', strtotime("+5 min")))
                ->setStatus($cronSchedule::STATUS_PENDING);

            $this->scheduleResource->save($cronSchedule);

        } catch (\Exception $e) {
            $this->logger->error('Regenerate page cache warmup url failed: ' . $e->getMessage());
        }
    }
}