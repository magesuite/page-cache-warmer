<?php

/** @var \Magento\Framework\ObjectManagerInterface $objectManager */
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var \Magento\Cms\Api\PageRepositoryInterface $pageRepository */
$pageRepository = $objectManager->create(\Magento\Cms\Api\PageRepositoryInterface::class);

$page = $pageRepository->getById(1);

$page->setWarmupPriority(10);

$pageRepository->save($page);
