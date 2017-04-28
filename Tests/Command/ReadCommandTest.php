<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedBundle\Tests\Command;

use FeedBundle\Command\ReadCommand;
use FeedBundle\Handler\FeedHandler;
use PHPUnit\Framework\TestCase;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Command\RouterDebugCommand;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;


class ReadCommandTest extends TestCase
{
    public function testWithCountInInput()
    {
        $tester = $this->createCommandTester();
        $ret = $tester->execute([
            'url' => ''
        ],
        [
            'count' => 5
        ]);

        $this->assertEquals(0, $ret, 'Feeded 5 items');
    }

    /**
     * @return CommandTester
     */
    private function createCommandTester()
    {
        $application = new Application();

        $command = new ReadCommand();
        $command->setContainer($this->getContainer());
        $application->add($command);

        return new CommandTester($application->find('read:feeds'));
    }

    private function getContainer()
    {
        $feedHandler = $this->getMockBuilder('FeedBundle\Handler\FeedHandlerInterface')->getMock();
        $feedHandler
            ->expects($this->any())
            ->method('getLastFeeds')
            ->will($this->returnValue(5))
        ;
        $container = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerInterface')->getMock();
        $container
            ->expects($this->exactly(1))
            ->method('get')
            ->with('feed.handler')
            ->will($this->returnValue($feedHandler))
        ;

        return $container;
    }

}
