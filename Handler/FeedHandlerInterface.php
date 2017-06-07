<?php

namespace Gubarev\Bundle\FeedBundle\Handler;

/**
 * Interface FeedHandlerInterface
 * @package Gubarev\Bundle\FeedBundle\Handler
 */
interface FeedHandlerInterface
{
    /**
     * @param string $url
     * @param int $count
     *
     */
    public function getLastFeeds(string $url, int $count);
}
