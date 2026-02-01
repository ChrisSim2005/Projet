<?php // Ouverture de la balise PHP

require_once __DIR__ . '/../core/Controller.php'; // Inclusion du contrôleur de base
require_once __DIR__ . '/../models/User.php'; // Inclusion du modèle Utilisateur

class AccountController extends Controller // Définition de la classe AccountController héritant de Controller
{ // Ouverture de la classe
    public function __construct() // Constructeur de la classe
    { // Ouverture du constructeur
        // Sécurité : l'utilisateur doit être connecté pour accéder à son compte
        if (!isset($_SESSION['user_id'])) { // Vérification si l'utilisateur est connecté via sa session
            $this->redirect('index.php?controller=auth&action=login'); // Redirection vers la page de connexion si non connecté
        } // Fin de la vérification
    } // Fin du constructeur

    public function index() // Méthode pour afficher la page de profil
    { // Ouverture de la méthode index
        $userModel = new User(); // Instanciation du modèle User
        $user = $userModel->findById($_SESSION['user_id']); // Récupération des données de l'utilisateur par son ID
        
        $success = null; // Initialisation de la variable de succès à null
        $error = null; // Initialisation de la variable d'erreur à null

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) { // Vérification d'une soumission POST de mise à jour
            $nom = htmlspecialchars($_POST['nom']); // Sécurisation du nom posté
            $prenom = htmlspecialchars($_POST['prenom']); // Sécurisation du prénom posté

            if ($userModel->updateProfile($_SESSION['user_id'], $nom, $prenom)) { // Tentative de mise à jour du profil en base de données
                $success = "Profil mis à jour avec succès"; // Message de succès
                // Update session
                $_SESSION['user_nom'] = $nom; // Mise à jour du nom dans la session
                $_SESSION['user_prenom'] = $prenom; // Mise à jour du prénom dans la session
                $user = $userModel->findById($_SESSION['user_id']); // Rafraîchissement des données utilisateur
            } else { // Si la mise à jour échoue
                $error = "Erreur lors de la mise à jour du profil"; // Message d'erreur
            } // Fin de la condition de mise à jour
        } // Fin de la vérification du formulaire POST

        $this->render('account/index', [ // Rendu de la vue correspondante
            'user' => $user, // Passage des données utilisateur à la vue
            'success' => $success, // Passage du message de succès à la vue
            'error' => $error // Passage du message d'erreur à la vue
        ]); // Fin de l'appel render
    } // Fin de la méthode index

    public function changePassword() // Méthode pour changer le mot de passe
    { // Ouverture de la méthode
        $userModel = new User(); // Instanciation du modèle User
        $user = $userModel->findById($_SESSION['user_id']); // Récupération des données utilisateur de session
        
        $success = null; // Initialisation du succès à null
        $error = null; // Initialisation de l'erreur à null

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) { // Vérification de la soumission du formulaire de mot de passe
            $old_mdp = $_POST['old_mdp']; // Récupération de l'ancien mot de passe
            $new_mdp = $_POST['new_mdp']; // Récupération du nouveau mot de passe
            $confirm_mdp = $_POST['confirm_mdp']; // Récupération de la confirmation

            if ($old_mdp === $user['mdp']) { // Vérification si l'ancien mot de passe correspond à celui en base
                if ($new_mdp === $confirm_mdp) { // Vérification si les deux nouveaux mots de passe concordent
                    if ($userModel->updatePassword($_SESSION['user_id'], $new_mdp)) { // Mise à jour du mot de passe en base
                        $success = "Mot de passe modifié avec succès"; // Message de succès
                    } else { // Si erreur lors de l'enregistrement
                        $error = "Erreur lors de la modification du mot de passe"; // Message d'erreur PDO
                    } // Fin de mise à jour
                } else { // Si les mots de passe ne correspondent pas
                    $error = "Les nouveaux mots de passe ne correspondent pas"; // Message d'erreur de correspondance
                } // Fin de vérification de correspondance
            } else { // Si l'ancien mot de passe est faux
                $error = "L'ancien mot de passe est incorrect"; // Message d'erreur de validation
            } // Fin de vérification de l'ancien mot de passe
        } // Fin de vérification POST

        $this->render('account/index', [ // Rendu de la vue de compte
            'user' => $user, // Envoi des données utilisateur
            'success_pwd' => $success, // Envoi du succès spécifique au mot de passe
            'error_pwd' => $error // Envoi de l'erreur spécifique au mot de passe
        ]); // Fin de l'appel render
    } // Fin de la méthode changePassword
} // Fin de la classe

