<?php
namespace MageSuite\PageCacheWarmer\Model\Entity;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;

class UrlsRepository implements \MageSuite\PageCacheWarmer\Api\EntityUrlsRepositoryInterface
{

    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Urls
     */
    private $urlsResource;
    /**
     * @var UrlsFactory
     */
    private $urlsFactory;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Urls\CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(
        \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Urls $urlsResource,
        \MageSuite\PageCacheWarmer\Model\Entity\UrlsFactory $urlsFactory,
        \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Urls\CollectionFactory $collectionFactory,
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

    public function save(\MageSuite\PageCacheWarmer\Api\Data\Entity\UrlsInterface $url)
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

    public function delete(\MageSuite\PageCacheWarmer\Api\Data\Entity\UrlsInterface $url)
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