<?php

namespace Gubarev\Bundle\FeedBundle\Utils;

use Gubarev\Bundle\FeedBundle\Entity\CategoryEntity;
use Gubarev\Bundle\FeedBundle\Entity\MediaEntity;
use Gubarev\Bundle\FeedBundle\Entity\NewsEntity;
use FeedIo\Feed\ItemInterface;

interface FeedEntityManagerInterface
{
    public function addCategory(ItemInterface $item): CategoryEntity;

    public function addNews(ItemInterface $item, CategoryEntity $category, ?MediaEntity $media): NewsEntity;

    public function addMedia(ItemInterface $item): ?MediaEntity;

    public function truncateTables();

    public function flushEntities();

    public function findTotalByConditions(string $class, array $criteria = []): int;

    public function findAllByConditions(string $class, array $criteria, array $orderBy = null, $limit = null, $offset = null): array;
}
