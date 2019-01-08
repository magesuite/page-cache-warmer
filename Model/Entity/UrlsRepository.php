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

    public function __construct(
        \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Urls $urlsResource,
        \MageSuite\PageCacheWarmer\Model\Entity\UrlsFactory $urlsFactory,
        \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Urls\CollectionFactory $collectionFactory
    )
    {

        $this->urlsResource = $urlsResource;
        $this->urlsFactory = $urlsFactory;
        $this->collectionFactory = $collectionFactory;
    }

    public function getById($id)
    {
        $page = $this->urlsFactory->create();
        $page->load($id);
        if (!$page->getId()) {
            throw new NoSuchEntityException(__('Link with id "%1" does not exist.', $id));
        }
        return $page;
    }

    public function save(\MageSuite\PageCacheWarmer\Api\Data\Entity\UrlsInterface $url)
    {
        try {
            $entityUrlsCollection = $this->collectionFactory->create();
            $entityUrlsCollection
                ->addFieldToFilter('entity_id', ['eq' => $url->getEntityId()])
                ->addFieldToFilter('entity_type', ['eq' => $url->getEntityType()])
                ->addFieldToFilter('tag', ['eq' => $url->getTag()]);

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