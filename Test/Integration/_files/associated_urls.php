<?php

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

///** @var \MageSuite\PageCacheWarmer\Model\Entity\Tags $tagEntity */
//$tagEntity = $objectManager->create(\MageSuite\PageCacheWarmer\Model\Entity\Tags::class);
//
///** @var \MageSuite\PageCacheWarmer\Api\EntityTagsRepositoryInterface $tagsRepository */
//$tagsRepository = $objectManager->create(\MageSuite\PageCacheWarmer\Api\EntityTagsRepositoryInterface::class);
//
///** @var \MageSuite\PageCacheWarmer\Model\Entity\Urls $urlsEntity */
//$urlsEntity = $objectManager->create(\MageSuite\PageCacheWarmer\Model\Entity\Urls::class);
//
///** @var \MageSuite\PageCacheWarmer\Api\EntityUrlsRepositoryInterface $urlsRepository */
//$urlsRepository = $objectManager->create(\MageSuite\PageCacheWarmer\Api\EntityUrlsRepositoryInterface::class);
//
///** @var \MageSuite\PageCacheWarmer\Api\EntityRelationsRepositoryInterface $relationsRepository */
//$relationsRepository = $objectManager->create(\MageSuite\PageCacheWarmer\Api\EntityRelationsRepositoryInterface::class);

/** @var \MageSuite\PageCacheWarmer\Service\AssociatedUrlsGenerator $associatedUrlsGenerator */
$associatedUrlsGenerator = $objectManager->create(\MageSuite\PageCacheWarmer\Service\AssociatedUrlsGenerator::class);

$sampleTags = [
    'cat_p_93',
    'cat_p_234',
    'cat_p_2',
    'cat_p_87',
    'cat_p_328',
    'cat_p_656',
    'cat_p_122',
    'cat_p_34',
    'cat_p_556',
    'cat_p_46',
    'cat_p_889',
    'cat_p_098',
    'cat_p_788',
];

//foreach ($sampleTags as $tag) {
//    $tagEntity->setTag($tag);
//    $tagsRepository->save($tagEntity);
//}

$sampleUrls = [
    [
        'entity_id' => '1',
        'controller' => 'index',
        'url' => 'creativeshop.me'
    ],
    [
        'entity_id' => '2',
        'controller' => 'category',
        'url' => 'creativeshop.me/catalog/category/id/2'
    ],
    [
        'entity_id' => '4',
        'controller' => 'category',
        'url' => 'creativeshop.me/catalog/category/id/4'
    ],
    [
        'entity_id' => '54',
        'controller' => 'product',
        'url' => 'creativeshop.me/catalog/product/id/54'
    ],
    [
        'entity_id' => '4',
        'controller' => 'product',
        'url' => 'creativeshop.me/catalog/product/id/4'
    ],
    [
        'entity_id' => '8',
        'controller' => 'page',
        'url' => 'creativeshop.me/about-us'
    ],
];

foreach ($sampleUrls as $url) {
    $associatedUrlsGenerator->addAssociatedUrlsToWarmup(implode(',', $sampleTags), $url['controller'], $url['url'], [$url['entity_id']]);
}




