<?php

require_once __DIR__ . '/../core/Controller.php'; // Charge la classe de base Controller
require_once __DIR__ . '/../models/User.php';     // Charge le modèle User

class AuthController extends Controller
{
    // Emails ayant les droits d'administration
    private $admin_emails = [
        'admin@example.com',
        'administrateur@ecommerce.com',
        'superadmin@ecommerce.com',
        'admin@ecommerce.com'
    ];

    // Gère la connexion des utilisateurs
    public function login()
    {
        $error = null; // Initialise l'erreur à vide

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupère et nettoie les données envoyées
            $email = htmlspecialchars(trim($_POST['email']));
            $mdp = $_POST['mdp'];

            if (!empty($email) && !empty($mdp)) {
                $userModel = new User(); // Instance du modèle User
                $user = $userModel->findByEmail($email); // Cherche par email

                // Vérifie si l'utilisateur existe et si le MDP correspond
                if ($user && $user['mdp'] === $mdp) { // Comparaison en clair pour compatibilité
                    // Enregistre les données essentielles en session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_nom'] = $user['nom'];
                    $_SESSION['user_prenom'] = $user['prenom'];

                    // Attribue le rôle 'admin' ou 'client' selon l'email
                    if (in_array($user['email'], $this->admin_emails)) {
                        $_SESSION['user_role'] = 'admin';
                    } else {
                        $_SESSION['user_role'] = 'client';
                    }

                    $this->redirect('index.php'); // Redirige vers l'accueil
                } else {
                    $error = "Email ou mot de passe incorrect"; // Erreur d'authentification
                }
            } else {
                $error = "Veuillez remplir tous les champs"; // Erreur champs vides
            }
        }

        $this->render('auth/login', ['error' => $error]); // Affiche la vue login
    }

    // Déconnecte l'utilisateur et détruit sa session
    public function logout()
    {
        session_destroy(); // Supprime toutes les données de session
        $this->redirect('index.php'); // Retour à l'accueil
    }

    // Gère l'inscription des nouveaux clients
    public function register()
    {
        $error = null; // Initialisation erreur
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupère les données postées
            $nom = htmlspecialchars($_POST['nom']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $email = htmlspecialchars($_POST['email']);
            $mdp = $_POST['mdp']; // MDP stocké en clair pour le moment
            $telephone = htmlspecialchars($_POST['telephone']);
            
            // Vérifie que tous les champs obligatoires sont fournis
             if(!empty($nom) && !empty($prenom) && !empty($email) && !empty($mdp) && !empty($telephone)){
                 $userModel = new User(); // Instance du modèle
                 // Tente la création en base de données
                 if($userModel->create($nom, $prenom, $email, $mdp, $telephone)) {
                     // Redirige vers la connexion après succès
                     $this->redirect('index.php?controller=auth&action=login');
                 } else {
                     $error = "Erreur lors de l'inscription"; // Erreur technique
                 }
             } else {
                 $error = "Tous les champs sont requis"; // Erreur données manquantes
             }
        }
        
        $this->render('auth/register', ['error' => $error]); // Affiche le formulaire
    }
}
