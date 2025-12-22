<?php

class Auth
{
    public static function check()
    {
        return isset($_SESSION['user']);
    }

    public static function requireLogin()
    {
        if (!self::check()) {
            header("Location: /TOnotDO/public/auth/login");
            exit;
        }
    }

    public static function user()
    {
        return $_SESSION['user'] ?? null;
    }

    public static function role($role)
    {
        self::requireLogin();

        if ($_SESSION['user']['role'] !== $role) {
            die("Access denied");
        }
    }
}
