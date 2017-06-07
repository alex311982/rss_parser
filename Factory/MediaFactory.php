<?php

namespace Gubarev\Bundle\FeedBundle\Factory;

use Gubarev\Bundle\FeedBundle\Entity\MediaEntity;

/**
 * Created by PhpStorm.
 * User: agubarev
 * Date: 6/2/2017
 * Time: 6:06 PM
 */
class MediaFactory implements FactoryInterface
{
    public static function fromArray(array $data)
    {
        return MediaEntity::getEntityFromArray(MediaEntity::class, $data);
    }
}
