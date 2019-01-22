<?php
namespace MageSuite\PageCacheWarmer\Model\Entity;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;

class TagsCleanupQueueRepository implements \MageSuite\PageCacheWarmer\Api\EntityTagsCleanupQueueRepositoryInterface
{


    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\TagsCleanupQueue
     */
    protected $tagsCleanupQueueResource;
    /**
     * @var TagsCleanupQueueFactory
     */
    protected $tagsCleanupQueueFactory;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\TagsCleanupQueue\CollectionFactory
     */
    protected $collectionFactory;

    public function __construct(
        \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\TagsCleanupQueue $tagsCleanupQueueResource,
        \MageSuite\PageCacheWarmer\Model\Entity\TagsCleanupQueueFactory $tagsCleanupQueueFactory,
        \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\TagsCleanupQueue\CollectionFactory $collectionFactory
    )
    {

        $this->tagsCleanupQueueResource = $tagsCleanupQueueResource;
        $this->tagsCleanupQueueFactory = $tagsCleanupQueueFactory;
        $this->collectionFactory = $collectionFactory;
    }

    public function getById($id)
    {
        $page = $this->tagsCleanupQueueFactory->create();
        $page->load($id);
        if (!$page->getId()) {
            throw new NoSuchEntityException(__('Link with id "%1" does not exist.', $id));
        }
        return $page;
    }

    public function save(\MageSuite\PageCacheWarmer\Api\Data\Entity\TagsCleanupQueueInterface $tag)
    {
        try {
            $entityUrlsCollection = $this->collectionFactory->create();
            $entityUrlsCollection
                ->addFieldToFilter('tag', ['eq' => $tag->getTag()]);

            if($entityUrlsCollection->getSize()) {
                return $tag;
            }

            $this->tagsCleanupQueueResource->save($tag);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the link: %1',
                $exception->getMessage()
            ));
        }
        return $tag;
    }

    public function delete(\MageSuite\PageCacheWarmer\Api\Data\Entity\TagsCleanupQueueInterface $tag)
    {
        try {
            $this->tagsCleanupQueueResource->delete($tag);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the link: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }
}