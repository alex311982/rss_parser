<?php

namespace Gubarev\Bundle\FeedBundle\Handler;

use Gubarev\Bundle\FeedBundle\Exception\FeederException;
use Gubarev\Bundle\FeedBundle\Utils\FeedEntityManagerInterface;
use FeedIo\FeedInterface;
use FeedIo\FeedIo;
use FeedIo\FeedIoException;

class FeedHandler implements FeedHandlerInterface
{
    /**
     * @var FeedIo
     */
    protected $feedParser;
    /**
     * @var int
     */
    protected $curlLimit;
    /**
     * @var FeedEntityManagerInterface
     */
    protected $feedEntityManager;

    public function __construct(
        FeedIo $feedParser,
        FeedEntityManagerInterface $feedEntityManager,
        int $curlLimit
    )
    {
        $this->feedParser = $feedParser;
        $this->feedEntityManager = $feedEntityManager;
        $this->curlLimit = $curlLimit;
    }

    /**
     * Curl request for RSS
     *
     * @param string $url
     * @param int $count
     * @return int
     */
    public function getLastFeeds(string $url, int $count): int
    {
        $i = 0;

        $feed = $this->process($url);
        $this->feedEntityManager->truncateTables();
        $count = $this->getLimit($count);

        foreach($feed as $i => $item) {
            $i++;
            $category = $this->feedEntityManager->addCategory($item);
            $media = $this->feedEntityManager->addMedia($item);
            $news = $this->feedEntityManager->addNews($item, $category, $media);

            if ($count == $i) {
                break;
            }
        }

        if ($i > 0) {
            $this->feedEntityManager->flushEntities();
        }

        return $i;
    }

    protected function getLimit($count)
    {
        return $count > 0  ? $count : $count = $this->curlLimit;
    }

    /**
     * @param string $url
     * @return FeedInterface
     * @throws FeederException
     */
    protected function process(string $url): FeedInterface
    {
        try {
            return $this->feedParser->read($url)->getFeed();
        } catch (FeedIoException $e) {
            throw new FeederException(FeederException::FEEDER_ERROR);
        }
    }
}
