<?php

namespace FeedBundle\Handler;

/**
 * Interface FeedHandlerInterface
 * @package FeedBundle\Handler
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
