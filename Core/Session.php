<?php

namespace Core;

class Session
{

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = '')
    {

        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }

        return $_SESSION[$key] ?? $default;
    }

    public static function unflash($key = '__flash')
    {
        unset($_SESSION[$key]);
    }
}