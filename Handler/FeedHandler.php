<?php

namespace FeedBundle\Handler;

use Doctrine\ORM\EntityManagerInterface;
use FeedBundle\Exception\FeederException;
use FeedBundle\Utils\FeedEntityManagerInterface;
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
     * @var EntityManagerInterface
     */
    protected $em;
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
        EntityManagerInterface $em,
        int $curlLimit,
        FeedEntityManagerInterface $feedEntityManager
    )
    {
        $this->feedParser = $feedParser;
        $this->em = $em;
        $this->curlLimit = $curlLimit;
        $this->feedEntityManager = $feedEntityManager;
    }

    /**
     * Curl request for RSS
     *
     * @param string $url
     * @param int $count
     * @return int
     * @throws FeederException
     */
    public function getLastFeeds(string $url, int $count): int
    {
        $i = 0;

        $feed = $this->process($url);
        $count = $this->getLimit($count);

        $this->truncateTables();

        foreach($feed as $i => $item) {
            $i++;
            $category = $this->feedEntityManager->addCategory($item);
            $media = $this->feedEntityManager->addMedia($item);
            $news = $this->feedEntityManager->addNews($item, $category, $media);

            if ($count == $i) {
                break;
            }
        }

        try {
            $this->em->flush();
        } catch (\Exception $e) {
            throw new FeederException(FeederException::ORM_ERROR_MSG);
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

    /**
     * @param string $metaName
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getRepository(string $metaName): \Doctrine\Common\Persistence\ObjectRepository
    {
        return $this->em->getRepository($metaName);
    }

    protected function truncateTables()
    {
        $this->getRepository('FeedBundle:NewsEntity')->truncate();
        $this->getRepository('FeedBundle:CategoryEntity')->truncate();
        $this->getRepository('FeedBundle:MediaEntity')->truncate();
    }
}
