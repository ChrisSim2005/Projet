<?php // Ouverture de la balise PHP

require_once __DIR__ . '/../core/Controller.php'; // Inclusion du contrôleur de base (classe mère)
require_once __DIR__ . '/../models/Product.php'; // Inclusion du modèle Produit pour gérer les données produit

class AdminController extends Controller // Définition de la classe AdminController héritant de Controller
{ // Ouverture de la classe
    // Sécurité : vérifie si l'utilisateur est bien admin
    public function __construct() // Constructeur de la classe AdminController
    { // Ouverture du constructeur
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') { // Vérification du rôle d'administrateur en session
            $this->redirect('index.php?controller=auth&action=login'); // Redirection vers la connexion si non autorisé
        } // Fin de la vérification de sécurité
    } // Fin du constructeur

    // Page d'accueil de l'admin (liste + formulaire d'ajout)
    public function index() // Méthode principale de l'administration
    { // Ouverture de la méthode index
        $productModel = new Product(); // Instanciation du modèle Produit
        
        $success = null; // Initialisation du message de succès
        $error = null; // Initialisation du message d'erreur
        
        // Traitement de l'ajout d'un produit
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter'])) { // Détection d'une soumission de formulaire d'ajout
            $nom = $_POST['produitNom']; // Récupération du nom du produit
            $prix = $_POST['prix']; // Récupération du prix
            $desc = $_POST['descrip']; // Récupération de la description
            $categorie = $_POST['categorie'] ?? null; // Récupération de la catégorie (optionnelle)
            $img = ''; // Initialisation du nom de l'image
            
             // Gestion du téléchargement de l'image
            if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) { // Vérification si un fichier a été téléchargé sans erreur
                $dossier = ROOT_DIR . 'public/uploads/'; // Définition du chemin du dossier de stockage des images
                if(!is_dir($dossier)) { // Vérification de l'existence du dossier
                    mkdir($dossier, 0777, true); // Création du dossier s'il n'existe pas
                } // Fin de vérification du dossier
                
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION); // Extraction de l'extension du fichier
                $nomFichier = uniqid() . '_' . time() . '.' . $extension; // Génération d'un nom de fichier unique
                
                // Formats autorisés
                $types = ['jpg', 'jpeg', 'png', 'gif', 'webp']; // Liste des extensions d'images autorisées
                if(in_array(strtolower($extension), $types)) { // Vérification de l'extension
                    // Déplace le fichier vers uploads
                    if(move_uploaded_file($_FILES['image']['tmp_name'], $dossier . $nomFichier)) { // Déplacement physique du fichier
                        $img = $nomFichier; // Affectation du nom de fichier final
                    } // Fin du déplacement
                } // Fin de vérification du type
            } // Fin de gestion de l'image
            
            // Enregistre en base de données
            if($productModel->create($nom, $desc, $prix, $img, $categorie)) { // Appel de la méthode de création en base
                $success = "Produit ajouté avec succès"; // Message de succès à afficher
            } else { // Si l'insertion échoue
                $error = "Erreur lors de l'ajout"; // Message d'erreur à afficher
            } // Fin du test d'enregistrement
        } // Fin du bloc POST ajouter
        
        // Récupère tout le catalogue pour l'affichage
        $produits = $productModel->findAll(); // Récupération de tous les produits pour la liste
        // Affiche la vue admin/index
        $this->render('admin/index', ['produits' => $produits, 'success' => $success, 'error' => $error]); // Rendu de la page admin avec données
    } // Fin de la méthode index

    // Page de modification d'un produit
    public function edit($id) // Méthode pour l'édition d'un produit spécifique
    { // Ouverture de la méthode edit
        $productModel = new Product(); // Instanciation du modèle Produit
        $produit = $productModel->findById($id); // Recherche des informations du produit par son ID

        if (!$produit) { // Si le produit n'existe pas en base
            $this->redirect('index.php?controller=admin&action=index'); // Redirection vers la liste
        } // Fin de vérification d'existence

        // Traitement de la mise à jour
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier'])) { // Détection d'une soumission de formulaire de modification
            $nom = $_POST['produitNom']; // Récupération du nouveau nom
            $prix = $_POST['prix']; // Récupération du nouveau prix
            $desc = $_POST['descrip']; // Récupération de la nouvelle description
            $categorie = $_POST['categorie']; // Récupération de la catégorie
            $img = null; // Initialisation de l'image (sera null si pas de changement)

            // Si une nouvelle image est fournie
            if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) { // Vérification du nouvel upload
                $dossier = ROOT_DIR . 'public/uploads/'; // Dossier destination
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION); // Extension du fichier
                $nomFichier = uniqid() . '_' . time() . '.' . $extension; // Nouveau nom unique
                
                $types = ['jpg', 'jpeg', 'png', 'gif', 'webp']; // Types autorisés
                if(in_array(strtolower($extension), $types)) { // Validation du type
                    if(move_uploaded_file($_FILES['image']['tmp_name'], $dossier . $nomFichier)) { // Transfert du fichier
                        $img = $nomFichier; // Stockage du nouveau nom
                    } // Fin transfert
                } // Fin validation type
            } // Fin gestion nouvel upload

            // Met à jour en base via le modèle
            if($productModel->update($id, $nom, $desc, $prix, $img, $categorie)) { // Exécution de la requête de mise à jour
                $this->redirect('index.php?controller=admin&action=index'); // Redirection après succès
            } else { // Si la mise à jour échoue
                $error = "Erreur lors de la modification"; // Message d'erreur
                $this->render('admin/edit', ['produit' => $produit, 'error' => $error]); // Rendu de la vue avec erreur
            } // Fin test mise à jour
        } else { // Si c'est un simple affichage (GET)
            $this->render('admin/edit', ['produit' => $produit]); // Affichage du formulaire d'édition
        } // Fin condition POST modifier
    } // Fin de la méthode edit

    // Action de suppression directe
    public function delete($id) // Méthode pour supprimer un produit
    { // Ouverture de la méthode delete
        if ($id) { // Vérification de la présence d'un ID
            $productModel = new Product(); // Instanciation du modèle
            if($productModel->delete($id)) { // Tentative de suppression en base
                 $this->redirect('index.php?controller=admin&action=index'); // Redirection après succès
            } else { // En cas d'échec de suppression
                 $this->redirect('index.php?controller=admin&action=index'); // Redirection par défaut
            } // Fin test suppression
        } // Fin vérification ID
        $this->redirect('index.php?controller=admin&action=index'); // Redirection de sécurité
    } // Fin de la méthode delete

    // Liste des commandes (gestion back-office)
    public function orders() // Méthode pour afficher les commandes clients
    { // Ouverture de la méthode
        require_once __DIR__ . '/../models/Order.php'; // Inclusion du modèle Order
        $orderModel = new Order(); // Instanciation du modèle Order
        $commandes = $orderModel->findAll(); // Récupération de toutes les commandes enregistrées
        
        $this->render('admin/orders', ['commandes' => $commandes]); // Affichage de la vue de gestion des commandes
    } // Fin de la méthode orders
} // Fin de la classe AdminController

