<?php

namespace Gubarev\Bundle\FeedBundle\Factory;

use Gubarev\Bundle\FeedBundle\Entity\NewsEntity;

/**
 * Created by PhpStorm.
 * User: agubarev
 * Date: 6/2/2017
 * Time: 6:06 PM
 */
class NewsFactory implements FactoryInterface
{
    public static function fromArray(array $data)
    {
        return NewsEntity::getEntityFromArray(NewsEntity::class, $data);
    }
}
