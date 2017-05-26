<?php

namespace FeedBundle\Tests\Handler\Fake;

use FeedIo\FeedInterface;

class FeedFake implements FeedInterface
{
    /**
     * @var \ArrayIterator
     */
    protected $items;

    public function __construct($data = [])
    {
        $this->items = new \ArrayIterator($data);
    }

    public function getUrl()
    {
        // TODO: Implement getUrl() method.
    }

    public function setUrl($url)
    {
        // TODO: Implement setUrl() method.
    }

    public function add(\FeedIo\Feed\ItemInterface $item)
    {
        // TODO: Implement add() method.
    }

    public function newItem()
    {
        // TODO: Implement newItem() method.
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return $this->items->current();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        return $this->items->next();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->items->key();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     *                 Returns true on success or false on failure.
     */
    public function valid()
    {
        return $this->items->valid();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        return $this->items->rewind();
    }

    public function getTitle()
    {
        // TODO: Implement getTitle() method.
    }

    public function setTitle($title)
    {
        // TODO: Implement setTitle() method.
    }

    public function getPublicId()
    {
        // TODO: Implement getPublicId() method.
    }

    public function setPublicId($id)
    {
        // TODO: Implement setPublicId() method.
    }

    public function getDescription()
    {
        // TODO: Implement getDescription() method.
    }

    public function setDescription($description)
    {
        // TODO: Implement setDescription() method.
    }

    public function getLastModified()
    {
        // TODO: Implement getLastModified() method.
    }

    public function setLastModified(\DateTime $lastModified)
    {
        // TODO: Implement setLastModified() method.
    }

    public function getLink()
    {
        // TODO: Implement getLink() method.
    }

    public function setLink($link)
    {
        // TODO: Implement setLink() method.
    }

    public function getCategories()
    {
        // TODO: Implement getCategories() method.
    }

    public function addCategory(\FeedIo\Feed\Node\CategoryInterface $category)
    {
        // TODO: Implement addCategory() method.
    }

    public function newCategory()
    {
        // TODO: Implement newCategory() method.
    }

    public function newElement()
    {
        // TODO: Implement newElement() method.
    }

    public function getValue($name)
    {
        // TODO: Implement getValue() method.
    }

    public function set($name, $value)
    {
        // TODO: Implement set() method.
    }

    public function getElementIterator($name)
    {
        // TODO: Implement getElementIterator() method.
    }

    public function hasElement($name)
    {
        // TODO: Implement hasElement() method.
    }

    public function addElement(\FeedIo\Feed\Node\ElementInterface $element)
    {
        // TODO: Implement addElement() method.
    }

    public function getAllElements()
    {
        // TODO: Implement getAllElements() method.
    }

    public function listElements()
    {
        // TODO: Implement listElements() method.
    }
}