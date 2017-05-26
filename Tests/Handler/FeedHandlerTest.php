<?php
/**
 * Created by PhpStorm.
 * User: agubarev
 * Date: 5/18/2017
 * Time: 7:08 PM
 */

namespace FeedBundle\Tests\Handler;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use FeedBundle\Exception\FeederException;
use FeedBundle\Handler\FeedHandler;
use FeedBundle\Tests\Handler\Fake\FeedFake;
use FeedBundle\Utils\FeedEntityManagerInterface;
use FeedIo\Feed\ItemInterface;
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
    protected $limit;

    protected function setUp()
    {
        $this->limit = 10;
    }

    /**
     * @dataProvider feedsProvider
     */
    public function testGetLastFeeds($data, $setCountInCommand, $expectedCount)
    {
        $feeder = $this->getFeeder();
        $feeder->expects($this->exactly($expectedCount))
            ->method('addCategory');
        $feeder->expects($this->exactly($expectedCount))
            ->method('addMedia');
        $feeder->expects($this->exactly($expectedCount))
            ->method('addNews');

        $handler = new FeedHandler($this->getFeedParser($data), $this->getEM(), $this->limit, $feeder);

        $this->assertEquals($expectedCount, $handler->getLastFeeds('', $setCountInCommand));
    }

    public function testGetLastFeedsReadException()
    {
        $feeder = $this->getFeeder();
        $feeder->expects($this->never())
            ->method('addCategory');
        $feeder->expects($this->never())
            ->method('addMedia');
        $feeder->expects($this->never())
            ->method('addNews');

        $handler = new FeedHandler($this->getFeedParser([], true), $this->getEM(true), $this->limit, $feeder);
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

    protected function getFeedParser($data, $isExceptionWillBeThrown = false) {
        $feed = new FeedFake($data);
        if (!$isExceptionWillBeThrown) {
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

    protected function getFeeder()
    {
        return $this->getMockBuilder(FeedEntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['addCategory', 'addNews', 'addMedia'])
            ->getMock();
    }

    public function feedsProvider()
    {
        $item = $this->createMock(ItemInterface::class);
        return [
            [[$item, $item, $item, $item, $item, $item, $item, $item, $item, $item], 5, 5],
            [[$item, $item, $item, $item, $item, $item, $item, $item, $item, $item], 0, 10],
            [[$item, $item, $item, $item, $item, $item, $item, $item, $item, $item], -10, 10],
            [[$item, $item, $item, $item, $item, $item, $item, $item, $item, $item], 20, 10],
            [[$item, $item, $item, $item, $item, $item, $item, $item, $item, $item, $item, $item], 20, 12],
            [[$item, $item, $item, $item, $item, $item, $item, $item, $item, $item, $item, $item], 7, 7],
            [[$item, $item, $item], 7, 3],
            [[], 7, 0],
        ];

    }
}
