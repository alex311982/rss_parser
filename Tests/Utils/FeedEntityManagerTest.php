<?php
/**
 * Created by PhpStorm.
 * User: agubarev
 * Date: 6/2/2017
 * Time: 8:15 PM
 */

namespace FeedBundle\Tests\Utils;

use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class FeedEntityManagerTest extends TestCase
{
    protected $em;

    protected function setUp()
    {
        $em = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['persist'])
            ->getMock();
        $em->expects($this->once())
            ->method('persist');
    }

    public function addCategoryTest()
    {
        $iterator = $this->getMockBuilder(\ArrayIterator::class)
            ->disableOriginalConstructor()
            ->setMethods(['current'])
            ->getMock();

        $iterator->expects($this->once())
            ->method('current')
            ->willReturn()

        $item = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCategories'])
            ->getMock();
        $item->expects($this->once())
            ->method('getCategories')
            ->willReturn()
    }
}
