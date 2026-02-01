<?php

abstract class Controller
{
    protected function render($view, $data = [])
    {
        extract($data);
        
        // Chemin vers la vue
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            die("Vue introuvable : " . $viewFile);
        }
    }

    protected function redirect($url)
    {
        header("Location: $url");
        exit();
    }
}
