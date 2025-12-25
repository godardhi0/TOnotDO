<?php

class Auth
{
    public static function check()
    {
        return isset($_SESSION['user']);
    }

    // Ensure user is logged in, otherwise redirect to login
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
        // Ensure user is logged in
        self::requireLogin();

        // If role is an array, check if user's role is in the array
        $allowedRoles = is_array($role) ? $role : [$role];
        
        if (!in_array($_SESSION['user']['role'], $allowedRoles)) {
            die("accès refusé");
        }
    }
}
