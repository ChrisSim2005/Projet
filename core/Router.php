<?php

class Router
{
    // Point d'entrée pour diriger les requêtes vers les bons contrôleurs
    public function run()
    {
        // Récupère le nom du contrôleur ou 'LandingController' par défaut
        $controllerName = isset($_GET['controller']) ? ucfirst($_GET['controller']) . 'Controller' : 'LandingController';
        // Récupère le nom de l'action ou 'index' par défaut
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';

        // Définit le chemin du fichier contrôleur
        $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';

        // Vérifie si le fichier existe sur le disque
        if (file_exists($controllerFile)) {
            require_once $controllerFile; // Charge le fichier
            if (class_exists($controllerName)) { // Vérifie si la classe existe
                $controller = new $controllerName(); // Initialise le contrôleur
                if (method_exists($controller, $action)) { // Vérifie si l'action existe
                    // Récupère tous les autres paramètres de l'URL (ID, etc)
                    $params = array_diff_key($_GET, ['controller' => '', 'action' => '']);
                    // Appelle la méthode dynamique avec les paramètres
                    call_user_func_array([$controller, $action], array_values($params));
                } else {
                    die("Action '$action' non trouvée"); // Erreur d'action
                }
            } else {
                die("Classe '$controllerName' introuvable"); // Erreur de classe
            }
        } else {
            die("Fichier contrôleur introuvable"); // Erreur de fichier
        }
    }
}
