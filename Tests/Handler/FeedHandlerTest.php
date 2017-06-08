<?php
/**
 * Created by PhpStorm.
 * User: agubarev
 * Date: 5/18/2017
 * Time: 7:08 PM
 */

namespace Gubarev\Bundle\FeedBundle\Tests\Handler;

use Gubarev\Bundle\FeedBundle\Exception\FeederException;
use Gubarev\Bundle\FeedBundle\Handler\FeedHandler;
use Gubarev\Bundle\FeedBundle\Tests\Handler\Fake\FeedFake;
use Gubarev\Bundle\FeedBundle\Utils\FeedEntityManagerInterface;
use FeedIo\Feed\ItemInterface;
use FeedIo\FeedIo;
use FeedIo\FeedIoException;
use PHPUnit\Framework\TestCase;
use FeedIo\Reader\Result;

/**
 * Class FeedHandlerTest
 * @package Gubarev\Bundle\FeedBundle\Tests\Handler
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
        $feeder = $this->getFeeder($expectedCount);

        $handler = new FeedHandler($this->getFeedParser($data), $feeder, $this->limit);

        $this->assertEquals($expectedCount, $handler->getLastFeeds('', $setCountInCommand));
    }

    public function testGetLastFeedsReadException()
    {
        $feeder = $this->getFeeder(null);

        $handler = new FeedHandler($this->getFeedParser([], true), $feeder, $this->limit);
        $this->expectException(FeederException::class);
        $handler->getLastFeeds('', 1);
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

    protected function getFeeder($expectedCount)
    {
        $feeder = $this->getMockBuilder(FeedEntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['addCategory', 'addNews', 'addMedia', 'truncateTables', 'flushEntities'])
            ->getMock();

        if ($expectedCount === 0) {
            $feeder->expects($this->once())
                ->method('truncateTables');

            $feeder->expects($this->never())
                ->method('addCategory');
            $feeder->expects($this->never())
                ->method('addMedia');
            $feeder->expects($this->never())
                ->method('addNews');
            $feeder->expects($this->never())
                ->method('flushEntities');
        } elseif ($expectedCount > 0) {
            $feeder->expects($this->once())
                ->method('truncateTables');

            $feeder->expects($this->exactly($expectedCount))
                ->method('addCategory');
            $feeder->expects($this->exactly($expectedCount))
                ->method('addMedia');
            $feeder->expects($this->exactly($expectedCount))
                ->method('addNews');
            $feeder->expects($this->once())
                ->method('flushEntities');
        } elseif ($expectedCount === null) {
            $feeder->expects($this->never())
                ->method('truncateTables');

            $feeder->expects($this->never())
                ->method('addCategory');
            $feeder->expects($this->never())
                ->method('addMedia');
            $feeder->expects($this->never())
                ->method('addNews');
            $feeder->expects($this->never())
                ->method('flushEntities');
        }

        return $feeder;
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
