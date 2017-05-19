<?php

namespace FeedBundle\Utils;

use ComponentBundle\Entity\CategoryEntity;
use ComponentBundle\Entity\MediaEntity;
use ComponentBundle\Entity\NewsEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use FeedIo\Feed\Item;
use FeedIo\Feed\ItemInterface;

class FeedEntityManager implements FeedEntityManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var ArrayCollection
     */
    protected $persistedCategories;

    public function __construct(
        EntityManagerInterface $em,
        ArrayCollection $category
    )
    {
        $this->em = $em;
        $this->persistedCategories = $category;
    }

    public function addCategory(ItemInterface $item): CategoryEntity
    {
        $categoryName = !is_null($item->getCategories()->current())
            ? $item->getCategories()->current()->getLabel() :
            'No name category';

        $key = md5($categoryName);

        $category = $this->persistedCategories->offsetExists($key)
            ? $this->persistedCategories->get($key)
            : null;

        $category = $category ?: new CategoryEntity($categoryName);
        $this->persistedCategories->set($key, $category);
        $this->em->persist($category);

        return $category;
    }

    public function addNews(ItemInterface $item, CategoryEntity $category, ?MediaEntity $media): NewsEntity
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

    public function addMedia(Item $item): ?MediaEntity
    {
        $media= null;

        if ($mediaFromFeed = $item->getMedias()->current()) {
            $media = new MediaEntity(
                $mediaFromFeed->getType(),
                $mediaFromFeed->getUrl(),
                $mediaFromFeed->getLength()
            );

            $this->em->persist($media);
        }

        return $media;
    }
}
