<?php

class Controller
{
    public function view($view, $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            die("View not found: $viewFile");
        }

        require __DIR__ . '/../views/layouts/main.php';
    }

    // Add this method
    public function redirect($path)
    {
        // Full URL path assuming your app is at /TOnotDO/public
        header("Location: /TOnotDO/public/$path");
        exit;
    }
}
