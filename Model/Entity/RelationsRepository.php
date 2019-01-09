<?php
namespace MageSuite\PageCacheWarmer\Model\Entity;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;

class RelationsRepository implements \MageSuite\PageCacheWarmer\Api\EntityRelationsRepositoryInterface
{

    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Relations
     */
    private $relationsResource;
    /**
     * @var RelationsFactory
     */
    private $relationsFactory;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Relations\CollectionFactory
     */
    private $collectionFactory;

    public function __construct(
        \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Relations $relationsResource,
        \MageSuite\PageCacheWarmer\Model\Entity\RelationsFactory $relationsFactory,
        \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Relations\CollectionFactory $collectionFactory
    )
    {
        $this->relationsResource = $relationsResource;
        $this->relationsFactory = $relationsFactory;
        $this->collectionFactory = $collectionFactory;
    }

    public function getById($id)
    {
        $page = $this->relationsFactory->create();
        $page->load($id);
        if (!$page->getId()) {
            throw new NoSuchEntityException(__('Link with id "%1" does not exist.', $id));
        }
        return $page;
    }

    public function save(\MageSuite\PageCacheWarmer\Api\Data\Entity\RelationsInterface $relation)
    {
        try {
            $entityUrlsCollection = $this->collectionFactory->create();
            $entityUrlsCollection
                ->addFieldToFilter('url_id', ['eq' => $relation->getUrlId()])
                ->addFieldToFilter('tag_id', ['eq' => $relation->getTagId()]);

            if($entityUrlsCollection->getSize()) {
                return $relation;
            }

            $this->relationsResource->save($relation);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the link: %1',
                $exception->getMessage()
            ));
        }
        return $relation;
    }

    public function delete(\MageSuite\PageCacheWarmer\Api\Data\Entity\RelationsInterface $relation)
    {
        try {
            $this->relationsResource->delete($relation);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the link: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }
}