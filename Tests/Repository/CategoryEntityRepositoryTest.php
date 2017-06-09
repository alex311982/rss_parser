<?php
/**
 * Created by PhpStorm.
 * User: agubarev
 * Date: 5/26/2017
 * Time: 5:56 PM
 */

namespace Gubarev\Bundle\FeedBundle\Tests\Repository;

use Gubarev\Bundle\FeedBundle\Entity\CategoryEntity;

class CategoryEntityRepositoryTest extends AbstractRepositoryTestCase
{
    public function testDeleteAll()
    {
        //test categories
        $category1 = new CategoryEntity('test_category_name');
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

    public function testFindTotalByConditions()
    {
        //test categories
        $category1 = new CategoryEntity('test_category_name');
        $this->em->persist($category1);

        //test categories
        $category2 = new CategoryEntity('test_category_name_no_searched_by_criteria');
        $this->em->persist($category2);

        $this->em->flush();

        $total = $this->repo
            ->findTotalByConditions([
                'name' => 'test_category_name'
            ]);

        $this->assertEquals($total, 1);
    }

    public function setupRepository()
    {
        $this->repo = $this->em
            ->getRepository(CategoryEntity::class);
    }
}
