<?php

class Auth
{
    public static function check()
    {
        return isset($_SESSION['user']);
    }

    public static function user()
    {
        return $_SESSION['user'] ?? null;
    }

    public static function role($role)
    {
        if (!self::check() || $_SESSION['user']['role'] !== $role) {
            die("Access denied");
        }
    }
}
