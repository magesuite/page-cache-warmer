<?php
namespace MageSuite\PageCacheWarmer\Plugin;

class RegenerateWarmupUrls
{
    /**
     * @var \MageSuite\PageCacheWarmer\Service\CronScheduler
     */
    private $cronScheduler;

    public function __construct(
        \MageSuite\PageCacheWarmer\Service\CronScheduler $cronScheduler
    )
    {
        $this->cronScheduler = $cronScheduler;
    }

    public function afterCleanType(\Magento\Framework\App\Cache\TypeList $subject, $result, $typeCode)
    {
        if ($typeCode == 'full_page') {
            $this->cronScheduler->schedule();
        }

        return $result;
    }
}