<?php

namespace FeedBundle\Utils;

use FeedBundle\Entity\CategoryEntity;
use FeedBundle\Entity\MediaEntity;
use FeedBundle\Entity\NewsEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use FeedBundle\Factory\CategoryFactory;
use FeedBundle\Factory\MediaFactory;
use FeedBundle\Factory\NewsFactory;
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
        $currentCategory = $item->getCategories()->current();
        $categoryName = !empty($currentCategory->getLabel())
            ? $currentCategory->getLabel() :
            'No name category';

        $key = md5($categoryName);

        $category = $this->persistedCategories->offsetExists($key)
            ? $this->persistedCategories->get($key)
            : null;

        $category = is_object($category) ? $category : CategoryFactory::fromArray([
            'name' => $categoryName,
        ]);

        $this->persistedCategories->set($key, $category);
        $this->em->persist($category);

        return $category;
    }

    public function addNews(ItemInterface $item, CategoryEntity $category, ?MediaEntity $media = null): NewsEntity
    {
        $news = NewsFactory::fromArray([
            'guid' => $item->getPublicId(),
            'category' => $category,
            'title' => $item->getTitle(),
            'description' => $item->getDescription(),
            'pubDate' => $item->getLastModified(),
            'link' => $item->getLink(),
            'media' => $media
        ]);

        $this->em->persist($news);

        return $news;
    }

    public function addMedia(ItemInterface $item): ?MediaEntity
    {
        $media = null;

        if ($mediaFromFeed = $item->getMedias()->current()) {
            $media = MediaFactory::fromArray([
                'type' => $mediaFromFeed->getType(),
                'url' => $mediaFromFeed->getUrl(),
                'length' =>$mediaFromFeed->getLength()
                ]);

            $this->em->persist($media);
        }

        return $media;
    }
}
