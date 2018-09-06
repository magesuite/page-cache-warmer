<?php
namespace Creativestyle\MageSuite\PageCacheWarmer\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;

class PageCacheWarmerRepository implements \Creativestyle\MageSuite\PageCacheWarmer\Api\PageCacheWarmerRepositoryInterface
{
    /**
     * @var \Creativestyle\MageSuite\PageCacheWarmer\Model\ResourceModel\PageCacheWarmer
     */
    protected $pageCacheWarmerResource;

    /**
     * @var \Creativestyle\MageSuite\PageCacheWarmer\Model\PageCacheWarmerFactory
     */
    protected $pageCacheWarmerFactory;

    public function __construct(
        \Creativestyle\MageSuite\PageCacheWarmer\Model\ResourceModel\PageCacheWarmer $pageCacheWarmerResource,
        \Creativestyle\MageSuite\PageCacheWarmer\Model\PageCacheWarmerFactory $pageCacheWarmerFactory
    )
    {
        $this->pageCacheWarmerResource = $pageCacheWarmerResource;
        $this->pageCacheWarmerFactory = $pageCacheWarmerFactory;
    }

    public function getById($id)
    {
        $link = $this->pageCacheWarmerFactory->create();
        $link->load($id);
        if (!$link->getId()) {
            throw new NoSuchEntityException(__('Link with id "%1" does not exist.', $id));
        }
        return $link;
    }

    public function save(\Creativestyle\MageSuite\PageCacheWarmer\Api\Data\PageCacheWarmerInterface $url)
    {
        try {
            $this->pageCacheWarmerResource->save($url);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the link: %1',
                $exception->getMessage()
            ));
        }
        return $url;
    }

    public function delete(\Creativestyle\MageSuite\PageCacheWarmer\Api\Data\PageCacheWarmerInterface $url)
    {
        try {
            $this->pageCacheWarmerResource->delete($url);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the link: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }
}