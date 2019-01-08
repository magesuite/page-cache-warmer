<?php
namespace MageSuite\PageCacheWarmer\Model\Entity;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;

class TagsRepository implements \MageSuite\PageCacheWarmer\Api\EntityTagsRepositoryInterface
{
    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Tags
     */
    private $tagsResource;
    /**
     * @var TagsFactory
     */
    private $tagsFactory;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Tags\CollectionFactory
     */
    private $collectionFactory;


    public function __construct(
        \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Tags $tagsResource,
        \MageSuite\PageCacheWarmer\Model\Entity\TagsFactory $tagsFactory,
        \MageSuite\PageCacheWarmer\Model\ResourceModel\Entity\Tags\CollectionFactory $collectionFactory
    )
    {

        $this->tagsResource = $tagsResource;
        $this->tagsFactory = $tagsFactory;
        $this->collectionFactory = $collectionFactory;
    }

    public function getById($id)
    {
        $page = $this->tagsFactory->create();
        $page->load($id);
        if (!$page->getId()) {
            throw new NoSuchEntityException(__('Link with id "%1" does not exist.', $id));
        }
        return $page;
    }

    public function save(\MageSuite\PageCacheWarmer\Api\Data\Entity\TagsInterface $tag)
    {
        try {
            $entityTagsCollection = $this->collectionFactory->create();
            $entityTagsCollection
                ->addFieldToFilter('tag', ['eq' => $tag->getTag()]);

            if($entityTagsCollection->getSize()) {
                return $tag;
            }

            $this->tagsResource->save($tag);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the link: %1',
                $exception->getMessage()
            ));
        }
        return $tag;
    }

    public function delete(\MageSuite\PageCacheWarmer\Api\Data\Entity\TagsInterface $tag)
    {
        try {
            $this->tagsResource->delete($tag);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the link: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }
}