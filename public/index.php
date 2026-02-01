<?php
session_start(); // Démarre la session utilisateur

// Chargement des fichiers de base
require_once __DIR__ . '/../config/config.php'; // Charge la configuration (URLs, Chemins)
require_once __DIR__ . '/../core/Router.php'; // Charge le routeur principal

$router = new Router(); // Crée une instance du routeur
$router->run(); // Lance l'application via le routeur
