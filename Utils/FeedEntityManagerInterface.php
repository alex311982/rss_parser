<?php

namespace FeedBundle\Utils;

use FeedBundle\Entity\CategoryEntity;
use FeedBundle\Entity\MediaEntity;
use FeedBundle\Entity\NewsEntity;
use FeedIo\Feed\ItemInterface;

interface FeedEntityManagerInterface
{
    public function addCategory(ItemInterface $item): CategoryEntity;

    public function addNews(ItemInterface $item, CategoryEntity $category, ?MediaEntity $media): NewsEntity;

    public function addMedia(ItemInterface $item): ?MediaEntity;
}
