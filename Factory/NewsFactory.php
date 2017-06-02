<?php

namespace FeedBundle\Factory;

use FeedBundle\Entity\NewsEntity;

/**
 * Created by PhpStorm.
 * User: agubarev
 * Date: 6/2/2017
 * Time: 6:06 PM
 */
class NewsFactory implements FactoryInterface
{
    public static function getInstance(array $data)
    {
        return NewsEntity::getEntityFromArray('FeedBundle\\Entity\\News', $data);
    }
}
