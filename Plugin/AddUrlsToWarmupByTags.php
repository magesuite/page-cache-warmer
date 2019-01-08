<?php
namespace MageSuite\PageCacheWarmer\Plugin;

class AddUrlsToWarmupByTags
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
    private $associatedUrlsGenerator;

    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \MageSuite\PageCacheWarmer\Service\AssociatedUrlsGenerator $associatedUrlsGenerator
    )
    {
        $this->request = $request;
        $this->associatedUrlsGenerator = $associatedUrlsGenerator;
    }

    public function aroundSetHeader(\Magento\Framework\App\ResponseInterface $subject, callable $proceed, $name, $value, $replace = false)
    {
        if($name == 'X-Magento-Tags'){
            $request = $this->request;

            $module = $request->getModuleName();
            $controller = $request->getControllerName();
            $action = $request->getActionName();

            $moduleCheck = ($module == self::CMS_MODULE || $module == self::CATALOG_MODULE) ? true : false;
            $controllerCheck = ($controller == self::PRODUCT_CONTROLLER || $controller == self::CATEGORY_CONTROLLER || $controller == self::CMS_CONTROLLER || $controller == self::HOMEPAGE_CONTROLLER) ? true : false;
            $actionCheck = ($action == self::ACTION || $action == self::HOMEPAGE_ACTION) ? true : false;

            if ($moduleCheck && $controllerCheck && $actionCheck) {
                $this->associatedUrlsGenerator->addAssociatedUrlsToWarmup($value, $controller, $request->getOriginalPathInfo(), $request->getParams());
            }
        }

        return $proceed($name, $value, $replace);
    }
}