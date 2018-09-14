<?php

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository */
$categoryRepository = $objectManager->create(\Magento\Catalog\Api\CategoryRepositoryInterface::class);

$category = $categoryRepository->get(333);

$category->setWarmupPriority(10);

$categoryRepository->save($category);

