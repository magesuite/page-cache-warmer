<?php
namespace MageSuite\PageCacheWarmer\Model\WarmupQueue;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;

class UrlRepository implements \MageSuite\PageCacheWarmer\Api\UrlRepositoryInterface
{
    /**
     * @var \MageSuite\PageCacheWarmer\Model\ResourceModel\WarmupQueue\Url
     */
    protected $urlResource;
    /**
     * @var \MageSuite\PageCacheWarmer\Model\WarmupQueue\UrlFactory
     */
    protected $urlFactory;


    public function __construct(
        \MageSuite\PageCacheWarmer\Model\ResourceModel\WarmupQueue\Url $urlResource,
        \MageSuite\PageCacheWarmer\Model\WarmupQueue\UrlFactory $urlFactory
    )
    {
        $this->urlResource = $urlResource;
        $this->urlFactory = $urlFactory;
    }

    public function getById($id)
    {
        $link = $this->urlFactory->create();
        $link->load($id);
        if (!$link->getId()) {
            throw new NoSuchEntityException(__('Link with id "%1" does not exist.', $id));
        }
        return $link;
    }

    public function save(\MageSuite\PageCacheWarmer\Api\Data\WarmupQueue\UrlInterface $url)
    {
        try {
            $this->urlResource->save($url);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the link: %1',
                $exception->getMessage()
            ));
        }
        return $url;
    }

    public function delete(\MageSuite\PageCacheWarmer\Api\Data\WarmupQueue\UrlInterface $url)
    {
        try {
            $this->urlResource->delete($url);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the link: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }
}
