<?php
namespace MageSuite\PageCacheWarmer\Model\Entity;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;

class UrlRepository implements \MageSuite\PageCacheWarmer\Api\EntityUrlRepositoryInterface
{

    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Url
     */
    protected $urlsResource;
    /**
     * @var UrlFactory
     */
    protected $urlsFactory;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Url\CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(
        \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Url $urlsResource,
        \MageSuite\PageCacheWarmer\Model\Entity\UrlFactory $urlsFactory,
        \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Url\CollectionFactory $collectionFactory,
        \Psr\Log\LoggerInterface $logger
    )
    {

        $this->urlsResource = $urlsResource;
        $this->urlsFactory = $urlsFactory;
        $this->collectionFactory = $collectionFactory;
        $this->logger = $logger;
    }

    public function getByUrl($url)
    {
        try {
            $entityUrlsCollection = $this->collectionFactory->create();
            $entityUrlsCollection
                ->addFieldToFilter('url', ['eq' => $url]);

            if($entityUrlsCollection->getSize()) {
                return $entityUrlsCollection->getFirstItem();
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return null;
    }

    public function save(\MageSuite\PageCacheWarmer\Api\Data\Entity\UrlInterface $url)
    {
        try {
            $entityUrlsCollection = $this->collectionFactory->create();
            $entityUrlsCollection
                ->addFieldToFilter('entity_id', ['eq' => $url->getEntityId()])
                ->addFieldToFilter('entity_type', ['eq' => $url->getEntityType()])
                ->addFieldToFilter('url', ['eq' => $url->getUrl()]);

            if($entityUrlsCollection->getSize()) {
                return $url;
            }

            $this->urlsResource->save($url);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the link: %1',
                $exception->getMessage()
            ));
        }
        return $url;
    }

    public function delete(\MageSuite\PageCacheWarmer\Api\Data\Entity\UrlInterface $url)
    {
        try {
            $this->urlsResource->delete($url);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the link: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }
}
