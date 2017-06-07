<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gubarev\Bundle\FeedBundle\Tests\Command;

use Gubarev\Bundle\FeedBundle\Command\ReadCommand;
use Gubarev\Bundle\FeedBundle\Exception\FeederException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ReadCommandTest extends TestCase
{
    /**
     * @dataProvider additionProvider
     */
    public function testWithCountInInput($url, $countIn, $isThrowException, $message)
    {
        $tester = $this->createCommandTester($url, $countIn, $isThrowException);
        $ret = $tester->execute([
            'url' => $url
        ],
        [
            'count' => $countIn
        ]);

        $this->assertContains($message, $tester->getDisplay());
    }

    /**
     * @return CommandTester
     */
    private function createCommandTester($url, ?int $count, $isThrowException)
    {
        $application = new Application();

        $command = new ReadCommand();
        $command->setContainer($this->getContainer($url, $count, $isThrowException));
        $application->add($command);

        return new CommandTester($application->find('read:feeds'));
    }

    private function getContainer($url, ?int $count, $isThrowException)
    {
        $feedHandler = $this->getMockBuilder('Gubarev\Bundle\FeedBundle\Handler\FeedHandlerInterface')->getMock();

        if (!$isThrowException) {
            $feedHandler
                ->expects($this->once())
                ->method('getLastFeeds')
                ->with($url)
                ->will($this->returnValue($count));
        } else {
            $feedHandler
                ->expects($this->once())
                ->method('getLastFeeds')
                ->with($url)
                ->will($this->throwException(new FeederException('test error message')));
        }

        $container = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerInterface')->getMock();
        $container
            ->expects($this->exactly(1))
            ->method('get')
            ->with('feed.handler')
            ->will($this->returnValue($feedHandler));

        return $container;
    }

    public function additionProvider()
    {
        return [
            ['test_url', 5, false, 'Feeded 5 items'],
            ['test_url', null, false, 'Feeded 0 items'],
            ['test_url', null, true, 'test error message'],
        ];
    }

}
