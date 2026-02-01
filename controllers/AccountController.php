<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';

class AccountController extends Controller
{
    public function __construct()
    {
        // Sécurité : l'utilisateur doit être connecté pour accéder à son compte
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('index.php?controller=auth&action=login');
        }
    }

    public function index()
    {
        $userModel = new User();
        $user = $userModel->findById($_SESSION['user_id']);
        
        $success = null;
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
            $nom = htmlspecialchars($_POST['nom']);
            $prenom = htmlspecialchars($_POST['prenom']);

            if ($userModel->updateProfile($_SESSION['user_id'], $nom, $prenom)) {
                $success = "Profil mis à jour avec succès";
                // Update session
                $_SESSION['user_nom'] = $nom;
                $_SESSION['user_prenom'] = $prenom;
                $user = $userModel->findById($_SESSION['user_id']); // Refresh data
            } else {
                $error = "Erreur lors de la mise à jour du profil";
            }
        }

        $this->render('account/index', [
            'user' => $user,
            'success' => $success,
            'error' => $error
        ]);
    }

    public function changePassword()
    {
        $userModel = new User();
        $user = $userModel->findById($_SESSION['user_id']);
        
        $success = null;
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
            $old_mdp = $_POST['old_mdp'];
            $new_mdp = $_POST['new_mdp'];
            $confirm_mdp = $_POST['confirm_mdp'];

            if ($old_mdp === $user['mdp']) {
                if ($new_mdp === $confirm_mdp) {
                    if ($userModel->updatePassword($_SESSION['user_id'], $new_mdp)) {
                        $success = "Mot de passe modifié avec succès";
                    } else {
                        $error = "Erreur lors de la modification du mot de passe";
                    }
                } else {
                    $error = "Les nouveaux mots de passe ne correspondent pas";
                }
            } else {
                $error = "L'ancien mot de passe est incorrect";
            }
        }

        $this->render('account/index', [
            'user' => $user,
            'success_pwd' => $success,
            'error_pwd' => $error
        ]);
    }
}
