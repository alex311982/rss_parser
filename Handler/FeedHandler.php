<?php

namespace FeedBundle\Handler;

use ComponentBundle\Entity\CategoryEntity;
use ComponentBundle\Entity\MediaEntity;
use ComponentBundle\Entity\NewsEntity;
use Doctrine\Common\Collections\ArrayCollection;
use FeedBundle\Exception\FeederException;
use Doctrine\ORM\EntityManager;
use FeedIo\Feed\Item;
use FeedIo\Feed\ItemInterface;
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
     * @var EntityManager
     */
    protected $em;
    /**
     * @var int
     */
    protected $curlLimit;

    protected $persistedCategories;

    public function __construct(
        FeedIo $feedParser,
        EntityManager $em,
        int $curlLimit
    )
    {
        $this->feedParser = $feedParser;
        $this->em = $em;
        $this->curlLimit = $curlLimit;
        $this->persistedCategories = new ArrayCollection();
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

        foreach($feed as $item) {
            $categoryName = !is_null($item->getCategories()->current())
                ? $item->getCategories()->current()->getLabel() :
                '';

            $category = $this->addCategory($categoryName);
            $media = $this->addMedia($item);
            $news = $this->addNews($item, $category, $media);

            if ($count === ++$i) {
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
        return $count > 0  ? : $count = $this->curlLimit;
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

    protected function addCategory(string $name): CategoryEntity
    {
        $key = md5($name);

        $category = $this->persistedCategories->offsetExists($key)
            ? $this->persistedCategories->get($key)
            : null;

        $category = $category ?: new CategoryEntity($name);
        $this->persistedCategories->set($key, $category);
        $this->em->persist($category);

        return $category;
    }

    protected function addNews(ItemInterface $item, CategoryEntity $category, ?MediaEntity $media): NewsEntity
    {
        $news = new NewsEntity(
            $item->getPublicId(),
            $category,
            $item->getTitle(),
            $item->getDescription(),
            $item->getLastModified(),
            $item->getLink(),
            $media
        );

        $this->em->persist($news);

        return $news;
    }

    /**
     * @param Item $item
     * @return MediaEntity | null
     */
    protected function addMedia(Item $item): ?MediaEntity
    {
        $media= null;

        if ($mediaFromFeed = $item->getMedias()->current()) {
            $media = new  MediaEntity(
                $mediaFromFeed->getType(),
                $mediaFromFeed->getUrl(),
                $mediaFromFeed->getLength()
            );

            $this->em->persist($media);
        }

        return $media;
    }

    /**
     * @param string $metaName
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository(string $metaName):\Doctrine\ORM\EntityRepository
    {
        return $this->em->getRepository($metaName);
    }


    protected function truncateTables()
    {
        $this->getRepository('ComponentBundle:NewsEntity')->truncate();
        $this->getRepository('ComponentBundle:CategoryEntity')->truncate();
        $this->getRepository('ComponentBundle:MediaEntity')->truncate();
    }
}
