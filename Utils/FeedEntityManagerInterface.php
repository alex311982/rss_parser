<?php

namespace FeedBundle\Utils;

use ComponentBundle\Entity\CategoryEntity;
use ComponentBundle\Entity\MediaEntity;
use ComponentBundle\Entity\NewsEntity;
use FeedIo\Feed\Item;
use FeedIo\Feed\ItemInterface;

interface FeedEntityManagerInterface
{
    public function addCategory(ItemInterface $item): CategoryEntity;

    public function addNews(ItemInterface $item, CategoryEntity $category, ?MediaEntity $media): NewsEntity;

    public function addMedia(Item $item): ?MediaEntity;
}
