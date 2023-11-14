<?php
namespace MageSuite\PageCacheWarmer\Model\Entity;

class CleanedTagsQueueRepository implements \MageSuite\PageCacheWarmer\Api\EntityCleanedTagsQueueRepositoryInterface
{


    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\CleanedTagsQueue
     */
    protected $cleanedTagsQueueResource;
    /**
     * @var CleanedTagsQueueFactory
     */
    protected $cleanedTagsQueueFactory;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\CleanedTagsQueue\CollectionFactory
     */
    protected $collectionFactory;

    public function __construct(
        \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\CleanedTagsQueue $cleanedTagsQueueResource,
        \MageSuite\PageCacheWarmer\Model\Entity\CleanedTagsQueueFactory $cleanedTagsQueueFactory,
        \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\CleanedTagsQueue\CollectionFactory $collectionFactory
    )
    {
        $this->cleanedTagsQueueResource = $cleanedTagsQueueResource;
        $this->cleanedTagsQueueFactory = $cleanedTagsQueueFactory;
        $this->collectionFactory = $collectionFactory;
    }

    public function getById($id)
    {
        $tagsQueue = $this->cleanedTagsQueueFactory->create();
        $tagsQueue->load($id);
        if (!$tagsQueue->getId()) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(__('Link with id "%1" does not exist.', $id));
        }
        return $tagsQueue;
    }

    public function save(\MageSuite\PageCacheWarmer\Api\Data\Entity\CleanedTagsQueueInterface $tag)
    {
        try {
            $urlsCollection = $this->collectionFactory->create();
            $urlsCollection
                ->addFieldToFilter('tag', ['eq' => $tag->getTag()]);

            if($urlsCollection->getSize()) {
                return $tag;
            }

            $this->cleanedTagsQueueResource->save($tag);
        } catch (\Exception $exception) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(__(
                'Could not save the link: %1',
                $exception->getMessage()
            ));
        }
        return $tag;
    }

    public function delete(\MageSuite\PageCacheWarmer\Api\Data\Entity\CleanedTagsQueueInterface $tag)
    {
        try {
            $this->cleanedTagsQueueResource->delete($tag);
        } catch (\Exception $exception) {
            throw new \Magento\Framework\Exception\CouldNotDeleteException(__(
                'Could not delete the link: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }
}
