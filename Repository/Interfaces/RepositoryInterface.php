<?php

namespace Gubarev\Bundle\FeedBundle\Repository\Interfaces;

interface RepositoryInterface
{
    public function truncate();

    public function findTotalByConditions(array $criteria = []): int;
}
