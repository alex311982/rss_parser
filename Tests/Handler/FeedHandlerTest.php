<?php
/**
 * Created by PhpStorm.
 * User: agubarev
 * Date: 5/18/2017
 * Time: 7:08 PM
 */

namespace FeedBundle\Tests\Handler;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use FeedBundle\Exception\FeederException;
use FeedBundle\Handler\FeedHandler;
use FeedIo\FeedInterface;
use FeedIo\FeedIo;
use FeedIo\FeedIoException;
use PHPUnit\Framework\TestCase;
use FeedIo\Reader\Result;

/**
 * Class FeedHandlerTest
 * @package FeedBundle\Tests\Handler
 */
class FeedHandlerTest extends TestCase
{
    protected $collection;

    protected $limit;

    protected function setUp()
    {
        $this->collection = $this->createMock(ArrayCollection::class);
        $this->limit = 10;
    }

    public function testGetLastFeeds()
    {
        $handler = new FeedHandler($this->getFeedParser(), $this->getEM(), $this->limit, $this->collection);

        $this->assertEquals(0, $handler->getLastFeeds('', 1));
    }

    public function testGetLastFeedsReadException()
    {
        $handler = new FeedHandler($this->getFeedParser(true), $this->getEM(true), $this->limit, $this->collection);
        $this->expectException(FeederException::class);
        $handler->getLastFeeds('', 1);
    }

    protected function getEM($isExceptionWillBeThrown = false) {
        if (!$isExceptionWillBeThrown) {
            $repository = $this->getMockBuilder(EntityRepository::class)
                ->disableOriginalConstructor()
                ->setMethods(['truncate'])
                ->getMock();
            $repository->expects($this->exactly(3))
                ->method('truncate');

            $em = $this->getMockBuilder(EntityManager::class)
                ->disableOriginalConstructor()
                ->setMethods(['getRepository', 'flush'])
                ->getMock();
            $em->expects($this->any())
                ->method('getRepository')
                ->willReturn($repository);
            $em->expects($this->once())
                ->method('flush');
        } else {
            $em =  $this->createMock(EntityManagerInterface::class);
        }

        return $em;
    }

    protected function getFeedParser($isExceptionWillBeThrown = false) {
        if (!$isExceptionWillBeThrown) {
            $feed = $this->createMock(FeedInterface::class);

            $result = $this->getMockBuilder(Result::class)
                ->disableOriginalConstructor()
                ->setMethods(['getFeed'])
                ->getMock();
            $result->expects($this->once())
                ->method('getFeed')
                ->willReturn($feed);

            $feedParser = $this->getMockBuilder(FeedIo::class)
                ->disableOriginalConstructor()
                ->setMethods(['read'])
                ->getMock();
            $feedParser->expects($this->once())
                ->method('read')
                ->willReturn($result);
        } else {
            $feedParser = $this->getMockBuilder(FeedIo::class)
                ->disableOriginalConstructor()
                ->setMethods(['read'])
                ->getMock();
            $feedParser->expects($this->once())
                ->method('read')
                ->will($this->throwException(new FeedIoException));
        }

        return $feedParser;
    }
}
