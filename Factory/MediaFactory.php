<?php

namespace FeedBundle\Factory;

use FeedBundle\Entity\MediaEntity;

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
        return MediaEntity::getEntityFromArray('FeedBundle\\Entity\\MediaEntity', $data);
    }
}
