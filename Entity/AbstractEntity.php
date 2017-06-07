<?php
/**
 * Created by PhpStorm.
 * User: agubarev
 * Date: 6/2/2017
 * Time: 6:24 PM
 */

namespace Gubarev\Bundle\FeedBundle\Entity;

abstract class AbstractEntity
{
    protected static $instance;

    public static function getEntityFromArray(string $class, array $data)
    {
        $reflection = new \ReflectionClass($class);
        static::$instance = $reflection->newInstanceWithoutConstructor();

        foreach ($data as $property => $value) {
            if (!preg_match('/[A-Z]$/', $property{0})) {
                $property = ucfirst($property);
            }
            static::$instance->{'set' . $property}($value);
        }

        return static::$instance;
    }

    public function __call($method, $args) {


        if (!method_exists(static::$instance, $method)) {
            throw new \Exception("unknown method [$method]");
        }

        return call_user_func_array(
            array(static::$instance, $method),
            $args
        );
    }
}
