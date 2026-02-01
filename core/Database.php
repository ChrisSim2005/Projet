<?php

class Database
{
    // Instance unique pour le Singleton
    private static $instance = null;
    private $pdo; // Objet PDO final

    private function __construct()
    {
        // Charge la configuration BDD depuis le fichier séparé
        $config = require __DIR__ . '/../config/database.php';

        // Construit la chaîne DSN pour MySQL
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset={$config['charset']}";

        try {
            // Crée la connexion avec les accès du fichier config
            $this->pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
        } catch (PDOException $e) {
            // Affiche l'erreur et arrête en cas d'échec
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    // Récupère l'instance unique (crée si inexistante)
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Retourne l'objet PDO utilisable pour les requêtes
    public function getConnection()
    {
        return $this->pdo;
    }
}
