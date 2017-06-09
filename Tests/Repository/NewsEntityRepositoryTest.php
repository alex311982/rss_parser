<?php
/**
 * Created by PhpStorm.
 * User: agubarev
 * Date: 5/26/2017
 * Time: 5:56 PM
 */

namespace Gubarev\Bundle\FeedBundle\Tests\Repository;

use Gubarev\Bundle\FeedBundle\Entity\CategoryEntity;
use Gubarev\Bundle\FeedBundle\Entity\NewsEntity;
use Gubarev\Bundle\FeedBundle\Repository\NewsEntityRepository;

class NewsEntityRepositoryTest extends AbstractRepositoryTestCase
{
    public function testDeleteAll()
    {
        //test category
        $category1 = new CategoryEntity('test_category');
        $this->em->persist($category1);

        //test news
        $news = new NewsEntity(
            'test_News_name',
            $category1,
            'test_news_title',
            'test_news_desc',
            new \DateTime,
            'test_news_link'
        );
        $this->em->persist($news);
        $this->em->flush();

        $this->repo
            ->truncate();
        $qb = $this->repo
            ->createQueryBuilder('entity');
        $qb->select('COUNT(entity)');
        $count = $qb->getQuery()->getSingleScalarResult();

        $this->assertEquals($count, 0);
    }

    public function testFindTotalByConditions()
    {
        //test category
        $category = new CategoryEntity('test_category');
        $this->em->persist($category);

        //test news
        $news1 = new NewsEntity(
            'test_News_name',
            $category,
            'test_news_title',
            'test_news_desc',
            new \DateTime,
            'test_news_link'
        );
        $this->em->persist($news1);

        //test news
        $news2 = new NewsEntity(
            'test_News_name',
            $category,
            'test_news_title_2',
            'test_news_desc',
            new \DateTime,
            'test_news_link'
        );
        $this->em->persist($news2);

        $this->em->flush();

        $total1 = $this->repo
            ->findTotalByConditions([
                'title' => 'test_news_title'
            ]);

        $this->assertEquals($total1, 1);
    }

    public function setupRepository()
    {
        $this->repo = $this->em
            ->getRepository(NewsEntity::class);
    }
}
