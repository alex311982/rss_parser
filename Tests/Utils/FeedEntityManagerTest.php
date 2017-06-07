<?php
/**
 * Created by PhpStorm.
 * User: agubarev
 * Date: 6/2/2017
 * Time: 8:15 PM
 */

namespace FeedBundle\Tests\Utils;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use FeedBundle\Entity\CategoryEntity;
use FeedBundle\Utils\FeedEntityManager;
use FeedIo\Feed\Item;
use FeedIo\Feed\Item\Media;
use FeedIo\Feed\Node\Category;
use PHPUnit\Framework\TestCase;

class FeedEntityManagerTest extends TestCase
{
    protected $em;

    protected function setUp()
    {
        $this->em = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['persist'])
            ->getMock();
    }

    public function testAddCategory()
    {
        $this->em->expects($this->once())
            ->method('persist');

        $iterator = $this->getMockBuilder(\ArrayIterator::class)
            ->disableOriginalConstructor()
            ->setMethods(['current'])
            ->getMock();

        $iterator->expects($this->once())
            ->method('current')
            ->willReturn((new Category)->setLabel('test label'));

        $item = $this->getMockBuilder(Item::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCategories'])
            ->getMock();

        $item->expects($this->any())
            ->method('getCategories')
            ->willReturn($iterator);

        $persister = $this->getMockBuilder(ArrayCollection::class)
            ->disableOriginalConstructor()
            ->setMethods(['offsetExists', 'get', 'set'])
            ->getMock();
        $persister->expects($this->once())
            ->method('offsetExists')
            ->willReturn(false);
        $persister->expects($this->never())
            ->method('get');
        $persister->expects($this->once())
            ->method('set')
            ->with(md5('test label'), $this->isType('object'));

        $savedCategory = (new FeedEntityManager($this->em, $persister))->addCategory($item);

        $this->assertEquals('test label', $savedCategory->getName());
    }

    public function testAddCategoryNoName()
    {
        $this->em->expects($this->once())
            ->method('persist');

        $iterator = $this->getMockBuilder(\ArrayIterator::class)
            ->disableOriginalConstructor()
            ->setMethods(['current'])
            ->getMock();

        $iterator->expects($this->once())
            ->method('current')
            ->willReturn(new Category);

        $item = $this->getMockBuilder(Item::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCategories'])
            ->getMock();

        $item->expects($this->any())
            ->method('getCategories')
            ->willReturn($iterator);

        $persister = $this->getMockBuilder(ArrayCollection::class)
            ->disableOriginalConstructor()
            ->setMethods(['offsetExists', 'get', 'set'])
            ->getMock();
        $persister->expects($this->once())
            ->method('offsetExists')
            ->willReturn(false);
        $persister->expects($this->never())
            ->method('get');
        $persister->expects($this->once())
            ->method('set')
            ->willReturn(false);

        $savedCategory = (new FeedEntityManager($this->em, $persister))->addCategory($item);

        $this->assertEquals('No name category', $savedCategory->getName());
    }

    public function testAddCategoryExist()
    {
        $this->em->expects($this->once())
            ->method('persist');

        $iterator = $this->getMockBuilder(\ArrayIterator::class)
            ->disableOriginalConstructor()
            ->setMethods(['current'])
            ->getMock();

        $iterator->expects($this->once())
            ->method('current')
            ->willReturn((new Category)->setLabel('test label'));

        $item = $this->getMockBuilder(Item::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCategories'])
            ->getMock();

        $item->expects($this->any())
            ->method('getCategories')
            ->willReturn($iterator);

        $persister = $this->getMockBuilder(ArrayCollection::class)
            ->disableOriginalConstructor()
            ->setMethods(['offsetExists', 'get', 'set'])
            ->getMock();
        $persister->expects($this->once())
            ->method('offsetExists')
            ->willReturn(true);
        $persister->expects($this->once())
            ->method('get')
            ->willReturn((new CategoryEntity('test label'))->setId(200));
        $persister->expects($this->once())
            ->method('set')
            ->willReturn(false);

        $savedCategory = (new FeedEntityManager($this->em, $persister))->addCategory($item);

        $this->assertEquals(200, $savedCategory->getId());
        $this->assertEquals('test label', $savedCategory->getName());
    }

    public function testAddNEws()
    {
        $this->em->expects($this->once())
            ->method('persist');

        $item = new Item();
        $item->setPublicId(100);
        $item->setTitle('title');
        $item->setDescription('desc');
        $item->setLastModified(new \DateTime);
        $item->setLink('test link');

        $persister = $this->getMockBuilder(ArrayCollection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $savedNews = (new FeedEntityManager($this->em, $persister))->addNews($item, new CategoryEntity('test category'));

        $this->assertEquals('test link', $savedNews->getLink());
        $this->assertEquals('test category', $savedNews->getCategory()->getName());
        $this->assertEquals('desc', $savedNews->getDescription());
        $this->assertEquals(100, $savedNews->getGuid());
    }

    public function testAddMedia()
    {
        $this->em->expects($this->once())
            ->method('persist');

        $iterator = $this->getMockBuilder(\ArrayIterator::class)
            ->disableOriginalConstructor()
            ->setMethods(['current'])
            ->getMock();

        $iterator->expects($this->once())
            ->method('current')
            ->willReturn((new Media)
                ->setType('test type')
                ->setUrl('test url')
                ->setLength(300)
            );

        $item = $this->getMockBuilder(Item::class)
            ->disableOriginalConstructor()
            ->setMethods(['getMedias'])
            ->getMock();

        $item->expects($this->any())
            ->method('getMedias')
            ->willReturn($iterator);

        $persister = $this->getMockBuilder(ArrayCollection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $savedMedia = (new FeedEntityManager($this->em, $persister))->addMedia($item);

        $this->assertEquals(300, $savedMedia->getLength());
        $this->assertEquals('test type', $savedMedia->getType());
        $this->assertEquals('test url', $savedMedia->getUrl());
    }

    public function testAddMediaNoExist()
    {
        $this->em->expects($this->never())
            ->method('persist');

        $iterator = $this->getMockBuilder(\ArrayIterator::class)
            ->disableOriginalConstructor()
            ->setMethods(['current'])
            ->getMock();

        $iterator->expects($this->once())
            ->method('current');

        $item = $this->getMockBuilder(Item::class)
            ->disableOriginalConstructor()
            ->setMethods(['getMedias'])
            ->getMock();

        $item->expects($this->any())
            ->method('getMedias')
            ->willReturn($iterator);

        $persister = $this->getMockBuilder(ArrayCollection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $savedMedia = (new FeedEntityManager($this->em, $persister))->addMedia($item);

        $this->assertNull($savedMedia);
    }
}
