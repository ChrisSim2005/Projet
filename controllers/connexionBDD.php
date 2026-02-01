<?php // Ouverture de la balise PHP
$host = 'localhost'; // Définition de l'hôte de la base de données
$dbname = 'ecommerce'; // Nom de la base de données
$username = 'root'; // Nom d'utilisateur pour la connexion
$password = 'chris'; // Mot de passe pour la connexion
$port = 3307; // Port spécifique utilisé pour MySQL/MariaDB

try { // Début du bloc de capture d'erreurs
    
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password); // Création de l'instance PDO pour la connexion
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Configuration pour lancer des exceptions en cas d'erreur SQL
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Configuration du mode de récupération par défaut en tableau associatif


} catch(PDOException $e) { // Capture de l'exception PDO si la connexion échoue
    die("Erreur de connexion à la base de données : " . $e->getMessage()); // Fin du script et affichage du message d'erreur
} // Fin du bloc try-catch
