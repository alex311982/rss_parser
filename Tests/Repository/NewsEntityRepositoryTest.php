<?php
/**
 * Created by PhpStorm.
 * User: agubarev
 * Date: 5/26/2017
 * Time: 5:56 PM
 */

namespace FeedBundle\Tests\Repository;

use FeedBundle\Entity\CategoryEntity;
use FeedBundle\Repository\CategoryEntityRepository;
use FeedBundle\Repository\NewsEntityRepository;

class NewsEntityRepositoryTest extends AbstractRepositoryTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
    /**
     * @var NewsEntityRepository
     */
    private $repo;
    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->em = $this->kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->repo = $this->em
            ->getRepository('FeedBundle:NewsEntity');
    }

    public function testDeleteAll()
    {
        //test categories
        $category1 = new CategoryEntity('test_News_name');
        $this->em->persist($category1);
        $this->em->flush();

        $this->repo
            ->truncate();
        $qb = $this->repo
            ->createQueryBuilder('entity');
        $qb->select('COUNT(entity)');
        $count = $qb->getQuery()->getSingleScalarResult();

        $this->assertEquals($count, 0);
    }
}
