<?php
namespace Creativestyle\MageSuite\PageCacheWarmer\Model;

class CacheWarmer
{
    /**
     * @var \Creativestyle\MageSuite\PageCacheWarmer\DataProviders\UrlDataProvider
     */
    protected $urlDataProvider;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $guzzleClient;

    /**
     * CacheWarmer constructor.
     * @param \Creativestyle\MageSuite\PageCacheWarmer\DataProviders\UrlDataProvider $urlDataProvider
     * @param \GuzzleHttp\Client $guzzleClient
     */
    public function __construct(
        \Creativestyle\MageSuite\PageCacheWarmer\DataProviders\UrlDataProvider $urlDataProvider,
        \GuzzleHttp\Client $guzzleClient
    )
    {
        $this->urlDataProvider = $urlDataProvider;
        $this->guzzleClient = $guzzleClient;
    }

    /**
     * Returns array of urls taken from page cache warmer.
     *
     * @return array
     */
    public function getUrlsArray()
    {
        return $this->urlDataProvider->getUrlsArray();
    }

    public function sendRequest()
    {
        $urls = $this->getUrlsArray();
        if(empty($urls)) {
            return;
        }

        $guzzleClient = new \GuzzleHttp\Client();
        foreach ($urls as $url) {
            $response = $guzzleClient->get($url);
            if($response->getStatusCode() == 200) {
                continue;
            }
        }
    }

}