<?php

namespace FeedBundle\Exception;

class FeederException extends \Exception
{
    const ORM_ERROR_MSG = 'Error while feeding';
    const FEEDER_ERROR = 'Error get feeds';

    static function formatMsg(array $values, string $subject): string
    {
        return str_replace(array_keys($values), array_values($values), $subject);
    }
}
