<?php
/**
 * Created by PhpStorm.
 * User: agubarev
 * Date: 6/2/2017
 * Time: 6:24 PM
 */

namespace FeedBundle\Entity;

abstract class AbstractEntity
{
    protected static $instance;

    public static function getEntityFromArray(string $class, array $data)
    {
        $reflection = new \ReflectionClass($class);
        static::$instance = $reflection->newInstanceWithoutConstructor();

        foreach ($data as $property) {
            static::$instance->{'set' . $property}();
        }

        return static::$instance;
    }

    public function __call($method, $args) {
        $method = $this->getInUppercase($method);

        if (!method_exists(static::$instance, $method)) {
            throw new \Exception("unknown method [$method]");
        }

        return call_user_func_array(
            array(static::$instance, $method),
            $args
        );
    }

    private function getInUppercase(string $method): string
    {
        if (!preg_match('/[A-Z]$/', $method{0})) {
            $method = ucfirst($method);
        }

        return $method;
    }
}
