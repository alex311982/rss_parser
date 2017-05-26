<?php
/**
 * Created by PhpStorm.
 * User: agubarev
 * Date: 5/26/2017
 * Time: 5:56 PM
 */

namespace FeedBundle\Tests\Repository;

class CategoryEntityRepositoryTest extends AbstractRepositoryTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->em = $this->kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testDeleteAll()
    {
        $products = $this->em
            ->getRepository('FeedBundle:Category')
            ->truncate();

        //$this->assertCount(1, $products);
    }
}
