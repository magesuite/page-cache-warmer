<?php
namespace MageSuite\PageCacheWarmer\Plugin;
/**
 * Created by PhpStorm.
 * User: blazejdoleska
 * Date: 05/09/2018
 * Time: 16:20
 */
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