<?php

namespace FeedBundle\Factory;

use FeedBundle\Entity\CategoryEntity;

/**
 * Created by PhpStorm.
 * User: agubarev
 * Date: 6/2/2017
 * Time: 6:06 PM
 */
class CategoryFactory implements FactoryInterface
{
    public static function getInstance(array $data)
    {
        return CategoryEntity::getEntityFromArray('FeedBundle\\Entity\\Category', $data);
    }
}
