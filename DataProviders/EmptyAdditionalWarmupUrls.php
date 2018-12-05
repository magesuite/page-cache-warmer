<?php
namespace MageSuite\PageCacheWarmer\DataProviders;

class EmptyAdditionalWarmupUrls implements \MageSuite\PageCacheWarmer\DataProviders\AdditionalWarmupUrlsInterface
{
    public function getAdditionalUrls()
    {
        return [];
    }
}