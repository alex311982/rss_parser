<?php

namespace Gubarev\Bundle\FeedBundle\Utils;

use Gubarev\Bundle\FeedBundle\Entity\CategoryEntity;
use Gubarev\Bundle\FeedBundle\Entity\MediaEntity;
use Gubarev\Bundle\FeedBundle\Entity\NewsEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Gubarev\Bundle\FeedBundle\Exception\FeederException;
use Gubarev\Bundle\FeedBundle\Factory\CategoryFactory;
use Gubarev\Bundle\FeedBundle\Factory\MediaFactory;
use Gubarev\Bundle\FeedBundle\Factory\NewsFactory;
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

    /**
     * FeedEntityManager constructor.
     * @param EntityManagerInterface $em
     * @param ArrayCollection $category
     */
    public function __construct(
        EntityManagerInterface $em,
        ArrayCollection $category
    )
    {
        $this->em = $em;
        $this->persistedCategories = $category;
    }

    /**
     * @param ItemInterface $item
     * @return CategoryEntity
     */
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

    /**
     * @param ItemInterface $item
     * @param CategoryEntity $category
     * @param MediaEntity|null $media
     * @return NewsEntity
     */
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

    /**
     * @param ItemInterface $item
     * @return MediaEntity|null
     */
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

    /**
     * @throws FeederException
     */
    public function truncateTables()
    {
        try {
            $this->em->getRepository(NewsEntity::class)->truncate();
            $this->em->getRepository(CategoryEntity::class)->truncate();
            $this->em->getRepository(MediaEntity::class)->truncate();
        } catch (\Exception $e) {
            throw new FeederException(FeederException::ORM_ERROR_MSG);
        }
    }

    /**
     * @throws FeederException
     */
    public function flushEntities()
    {
        try {
            $this->em->flush();
        } catch (\Exception $e) {
            throw new FeederException(FeederException::ORM_ERROR_MSG);
        }
    }
}
