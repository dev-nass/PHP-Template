<?php

namespace Core;

class Session
{

    public static function set($rootKey, $childKey, $value)
    {
        $_SESSION[$rootKey] = [
            $childKey => $value,
        ];
    }

    public static function get($rootKey, $childKey, $default = '')
    {

        if(isset($_SESSION[$rootKey][$childKey])) {
            return $_SESSION[$rootKey][$childKey];
        }

        return $_SESSION[$rootKey] ?? $default;
    }

    public static function unflash()
    {
        unset($_SESSION['__flash']);
    }
}