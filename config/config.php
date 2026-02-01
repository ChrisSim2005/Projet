<?php

// Définit le chemin absolu vers la racine du projet sur le disque
define('ROOT_DIR', dirname(__DIR__) . DIRECTORY_SEPARATOR);

// Détermine si on utilise HTTP ou HTTPS
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
// Récupère le nom de domaine (ex: localhost)
$host = $_SERVER['HTTP_HOST'];
// Récupère le chemin du script actuel
$script = $_SERVER['SCRIPT_NAME'];

// Construit l'URL de base dynamiquement
$base_url = $protocol . "://" . $host . $script;
// Enlève le fichier index.php de l'URL
$base_url = str_replace('/index.php', '', $base_url);

// S'assure que l'URL pointe vers le dossier public
if (strpos($base_url, '/public') === false) {
    $base_url = rtrim($base_url, '/') . '/public/';
} else {
    // Garde le chemin jusqu'au dossier public
    $base_url = substr($base_url, 0, strpos($base_url, '/public') + 7) . '/';
}

// Remplace les anti-slashs Windows par des slashs web
$base_url = str_replace('\\', '/', $base_url);
// Nettoie les doubles slashs éventuels
$base_url = preg_replace('#(?<=http://|https://)//+#', '/', $base_url);

// Définit la constante utilisable dans tout le projet
define('BASE_URL', $base_url);
