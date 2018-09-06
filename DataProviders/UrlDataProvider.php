<?php
namespace Creativestyle\MageSuite\PageCacheWarmer\DataProviders;

class UrlDataProvider
{
    protected $urlsString = '';
    protected $scopeConfig;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function setUrlsString($urlsString)
    {
        $this->urlsString = $urlsString;

        return $this;
    }
    /**
     * Returns array of urls taken from page cache warmer.
     *
     * @return array
     */
    public function getUrlsArray()
    {
        if($this->urlsString != ''){
            $configUrls = $this->urlsString;
        } else {
            $configUrls = $this->scopeConfig->getValue('pagecachewarmer/configuration/warmer_urls');
        }

        if(!$configUrls){
            return [];
        }

        $configText = trim($configUrls);
        $urlsArray = explode("\r\n", $configText);

        return $urlsArray;
    }
}