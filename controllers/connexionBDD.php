<?php
$host = 'localhost';
$dbname = 'ecommerce';
$username = 'root';
$password = 'chris';
$port = 3307; 

try {
    
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


} catch(PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}