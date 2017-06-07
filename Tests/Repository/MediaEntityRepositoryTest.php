<?php
/**
 * Created by PhpStorm.
 * User: agubarev
 * Date: 5/26/2017
 * Time: 5:56 PM
 */

namespace Gubarev\Bundle\FeedBundle\Tests\Repository;

use Gubarev\Bundle\FeedBundle\Entity\CategoryEntity;
use Gubarev\Bundle\FeedBundle\Entity\MediaEntity;
use Gubarev\Bundle\FeedBundle\Repository\MediaEntityRepository;

class MediaEntityRepositoryTest extends AbstractRepositoryTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
    /**
     * @var MediaEntityRepository
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
            ->getRepository(MediaEntity::class);
    }

    public function testDeleteAll()
    {
        //test categories
        $category1 = new CategoryEntity('test_Media_name');
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
