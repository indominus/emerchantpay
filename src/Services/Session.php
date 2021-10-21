<?php

namespace App\Services;

class Session
{

    public static function start()
    {
        session_start();
    }

    public function has($name)
    {
        return $_SESSION[$name] ?? false;
    }

    public function get($name, $default = null)
    {
        return $this->has($name) ? $_SESSION[$name] : $default;
    }

    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }
}
