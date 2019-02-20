<?php
namespace MageSuite\PageCacheWarmer\Plugin;

class GenerateAssociatedUrlsToWarmup
{
    const CMS_MODULE = 'cms';
    const CATALOG_MODULE = 'catalog';

    const CATEGORY_CONTROLLER = 'category';
    const PRODUCT_CONTROLLER = 'product';
    const CMS_CONTROLLER = 'page';
    const HOMEPAGE_CONTROLLER = 'index';

    const ACTION = 'view';
    const HOMEPAGE_ACTION = 'index';

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;
    /**
     * @var \MageSuite\PageCacheWarmer\Service\AssociatedUrlsGenerator
     */
    protected $associatedUrlsGenerator;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var \MageSuite\PageCacheWarmer\Helper\Configuration
     */
    private $configuration;

    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \MageSuite\PageCacheWarmer\Service\AssociatedUrlsGenerator $associatedUrlsGenerator,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \MageSuite\PageCacheWarmer\Helper\Configuration $configuration
    )
    {
        $this->request = $request;
        $this->associatedUrlsGenerator = $associatedUrlsGenerator;
        $this->storeManager = $storeManager;
        $this->configuration = $configuration;
    }

    public function aroundSetHeader(\Magento\Framework\App\ResponseInterface $subject, callable $proceed, $name, $value, $replace = false)
    {
        if($name == 'X-Magento-Tags' && $this->configuration->isGatheringTagsEnabled()){
            $request = $this->request;

            $module = $request->getModuleName();
            $controller = $request->getControllerName();
            $action = $request->getActionName();

            $moduleCheck = ($module == self::CMS_MODULE || $module == self::CATALOG_MODULE) ? true : false;
            $controllerCheck = ($controller == self::PRODUCT_CONTROLLER || $controller == self::CATEGORY_CONTROLLER || $controller == self::CMS_CONTROLLER || $controller == self::HOMEPAGE_CONTROLLER) ? true : false;
            $actionCheck = ($action == self::ACTION || $action == self::HOMEPAGE_ACTION) ? true : false;

            if ($moduleCheck && $controllerCheck && $actionCheck) {
                $baseUrl = $this->storeManager->getStore()->getBaseUrl();
                $requesUrl = rtrim($baseUrl, '/') . $request->getOriginalPathInfo();
                $this->associatedUrlsGenerator->addTagToUrlRelations($value, $controller, $requesUrl, $request->getParams());
            }
        }

        return $proceed($name, $value, $replace);
    }
}