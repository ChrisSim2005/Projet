<?php // Ouverture de la balise PHP

require_once __DIR__ . '/../core/Controller.php'; // Inclusion de la classe de base Controller pour l'héritage
require_once __DIR__ . '/../models/User.php';     // Inclusion du modèle User pour la gestion des utilisateurs

class AuthController extends Controller // Définition de la classe AuthController pour gérer l'authentification
{ // Ouverture de la classe
    // Liste des emails bénéficiant des privilèges d'administrateur
    private $admin_emails = [ // Déclaration d'une propriété privée contenant les emails admin
        'admin@example.com', // Email admin 1
        'administrateur@ecommerce.com', // Email admin 2
        'superadmin@ecommerce.com', // Email admin 3
        'admin@ecommerce.com' // Email admin 4
    ]; // Fin de la liste des emails admin

    // Gère la connexion des utilisateurs au système
    public function login() // Méthode pour l'affichage et le traitement du login
    { // Ouverture de la méthode login
        $error = null; // Initialisation de la variable d'erreur à vide par défaut

        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Vérification si le formulaire a été soumis en POST
            // Récupère et nettoie les données envoyées par l'utilisateur
            $email = htmlspecialchars(trim($_POST['email'])); // Nettoyage et sécurisation de l'email
            $mdp = $_POST['mdp']; // Récupération du mot de passe (vérifié plus tard)

            if (!empty($email) && !empty($mdp)) { // Vérification que les deux champs ne sont pas vides
                $userModel = new User(); // Création d'une instance du modèle User
                $user = $userModel->findByEmail($email); // Recherche de l'utilisateur en base via son email

                // Vérifie si l'utilisateur existe en base et si le mot de passe correspond
                if ($user && $user['mdp'] === $mdp) { // Comparaison directe du mot de passe (format texte brut)
                    // Enregistre les informations essentielles de l'utilisateur dans la session PHP
                    $_SESSION['user_id'] = $user['id']; // Stockage de l'ID utilisateur
                    $_SESSION['user_email'] = $user['email']; // Stockage de l'email
                    $_SESSION['user_nom'] = $user['nom']; // Stockage du nom
                    $_SESSION['user_prenom'] = $user['prenom']; // Stockage du prénom

                    // Attribue dynamiquement le rôle 'admin' ou 'client' selon l'email de l'utilisateur
                    if (in_array($user['email'], $this->admin_emails)) { // Si l'email est dans la liste admin
                        $_SESSION['user_role'] = 'admin'; // Attribution du rôle administrateur
                    } else { // Sinon (utilisateur standard)
                        $_SESSION['user_role'] = 'client'; // Attribution du rôle client
                    } // Fin de l'attribution du rôle

                    $this->redirect('index.php'); // Redirection vers la page d'accueil après succès
                } else { // Si l'email n'existe pas ou le mot de passe est faux
                    $error = "Email ou mot de passe incorrect"; // Définition du message d'erreur d'authentification
                } // Fin de vérification d'identité
            } else { // Si l'un des champs du formulaire est resté vide
                $error = "Veuillez remplir tous les champs"; // Définition du message d'erreur de saisie
            } // Fin de vérification de remplissage
        } // Fin du bloc de traitement POST

        $this->render('auth/login', ['error' => $error]); // Appel de la vue login en lui passant l'éventuelle erreur
    } // Fin de la méthode login

    // Déconnecte l'utilisateur actif et détruit complètement sa session
    public function logout() // Méthode de déconnexion
    { // Ouverture de la méthode logout
        session_destroy(); // Destruction de toutes les données de la session en cours
        $this->redirect('index.php'); // Redirection automatique vers la page d'accueil du site
    } // Fin de la méthode logout

    // Gère l'inscription de nouveaux clients sur la plateforme
    public function register() // Méthode pour l'affichage et le traitement de l'inscription
    { // Ouverture de la méthode register
        $error = null; // Initialisation du message d'erreur à null par défaut
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') { // Détection d'une soumission de données par formulaire POST
            // Récupère et sécurise les données postées par le nouvel utilisateur
            $nom = htmlspecialchars($_POST['nom']); // Sécurisation du nom de famille
            $prenom = htmlspecialchars($_POST['prenom']); // Sécurisation du prénom
            $email = htmlspecialchars($_POST['email']); // Sécurisation de l'adresse email
            $mdp = $_POST['mdp']; // Récupération du mot de passe (stocké en clair pour le moment)
            $telephone = htmlspecialchars($_POST['telephone']); // Sécurisation du numéro de téléphone
            
            // Vérifie scrupuleusement que tous les champs obligatoires ont été fournis
             if(!empty($nom) && !empty($prenom) && !empty($email) && !empty($mdp) && !empty($telephone)){ // Test de non vacuité
                 $userModel = new User(); // Création d'une instance du modèle utilisateur
                 // Tente de créer l'entrée correspondante dans la base de données
                 if($userModel->create($nom, $prenom, $email, $mdp, $telephone)) { // Appel à la méthode de création
                     // Redirige l'utilisateur vers la page de connexion après son succès d'inscription
                     $this->redirect('index.php?controller=auth&action=login'); // Passage à l'étape suivante
                 } else { // En cas d'échec technique lors de l'insertion SQL (ex: email déjà pris)
                     $error = "Erreur lors de l'inscription"; // Définition du message d'erreur technique
                 } // Fin du test de création
             } else { // Si au moins un champ requis est manquant lors de la soumission
                 $error = "Tous les champs sont requis"; // Définition du message d'erreur de validation
             } // Fin de vérification des champs
        } // Fin du bloc de traitement POST
        
        $this->render('auth/register', ['error' => $error]); // Affichage du formulaire d'inscription avec l'erreur si présente
    } // Fin de la méthode register
} // Fin de la classe AuthController

