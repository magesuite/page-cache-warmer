<?php
namespace Creativestyle\MageSuite\PageCacheWarmer\Plugin;
/**
 * Created by PhpStorm.
 * User: blazejdoleska
 * Date: 05/09/2018
 * Time: 16:20
 */
class RegenerateWarmupUrls
{
    /**
     * @var \Creativestyle\MageSuite\PageCacheWarmer\Service\RegenerateUrls
     */
    private $regenerateUrls;

    public function __construct(
        \Creativestyle\MageSuite\PageCacheWarmer\Service\RegenerateUrls $regenerateUrls
    )
    {
        $this->regenerateUrls = $regenerateUrls;
    }

    public function afterCleanType(\Magento\Framework\App\Cache\TypeList $subject, $result, $typeCode)
    {
        if ($typeCode == 'full_page') {
            $this->regenerateUrls->regenerate();
        }

        return $result;
    }
}