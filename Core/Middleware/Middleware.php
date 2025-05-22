<?php

namespace Core\Middleware;

class Middleware
{

    public const MAP = [
        'guest' => Guest::class,
        'auth' => Authenticated::class,
    ];

    public static function resolve($key, $role = "")
    {

        if(! $key) {
            return;
        }

        $middleware = static::MAP[$key] ?? false;

        if(! $middleware) {
            throw new \Exception("No matching middleware found for key {$key}");
        }

        $instance = new $middleware;
        $instance->handle($role);
    }
}