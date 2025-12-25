<?php

class Controller
{
    public function view($view, $data = [])
    {
        // Extract data for use in the view
        
        extract($data);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            die("Vue non trouvée: $viewFile");
        }

        require __DIR__ . '/../views/layouts/main.php';
    }

    public function redirect($path)
    {
        // Full URL path assuming your app is at /TOnotDO/public
        header("Location: /TOnotDO/public/$path");
        exit;
    }
}
