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

class MediaEntityRepositoryTest extends AbstractRepositoryTestCase
{
    public function testDeleteAll()
    {
        //test media
        $media = new MediaEntity('test_type', 'test_url', 200);
        $this->em->persist($media);
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
        //test media
        $media1 = new MediaEntity('test_type', 'test_url', 200);
        $this->em->persist($media1);

        //test media
        $media2 = new  MediaEntity('test_type', 'test_url_2', 300);
        $this->em->persist($media2);

        //test media
        $media3 = new  MediaEntity('test_type_2', 'test_url', 400);
        $this->em->persist($media3);

        $this->em->flush();

        $total1 = $this->repo
            ->findTotalByConditions([
                'type' => 'test_type'
            ]);

        $this->assertEquals($total1, 2);

        $total2 = $this->repo
            ->findTotalByConditions([
                'url' => 'test_url'
            ]);

        $this->assertEquals($total2, 2);

        $total3 = $this->repo
            ->findTotalByConditions([
                'length' => 300
            ]);

        $this->assertEquals($total3, 1);
    }

    public function setupRepository()
    {
        $this->repo = $this->em
            ->getRepository(MediaEntity::class);
    }
}
