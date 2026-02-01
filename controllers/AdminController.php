<?php

require_once __DIR__ . '/../core/Controller.php'; // Charge la classe mère
require_once __DIR__ . '/../models/Product.php'; // Charge le modèle Produit

class AdminController extends Controller
{
    // Sécurité : vérifie si l'utilisateur est bien admin
    public function __construct()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('index.php?controller=auth&action=login'); // Redirige sinon
        }
    }

    // Page d'accueil de l'admin (liste + formulaire d'ajout)
    public function index()
    {
        $productModel = new Product();
        
        $success = null;
        $error = null;
        
        // Traitement de l'ajout d'un produit
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter'])) {
            $nom = $_POST['produitNom'];
            $prix = $_POST['prix'];
            $desc = $_POST['descrip'];
            $categorie = $_POST['categorie'] ?? null;
            $img = '';
            
             // Gestion du téléchargement de l'image
            if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $dossier = ROOT_DIR . 'public/uploads/'; // Dossier cible
                if(!is_dir($dossier)) {
                    mkdir($dossier, 0777, true); // Crée le dossier s'il n'existe pas
                }
                
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $nomFichier = uniqid() . '_' . time() . '.' . $extension; // Nom unique
                
                // Formats autorisés
                $types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if(in_array(strtolower($extension), $types)) {
                    // Déplace le fichier vers uploads
                    if(move_uploaded_file($_FILES['image']['tmp_name'], $dossier . $nomFichier)) {
                        $img = $nomFichier;
                    }
                }
            }
            
            // Enregistre en base de données
            if($productModel->create($nom, $desc, $prix, $img, $categorie)) {
                $success = "Produit ajouté avec succès";
            } else {
                $error = "Erreur lors de l'ajout";
            }
        }
        
        // Récupère tout le catalogue pour l'affichage
        $produits = $productModel->findAll();
        // Affiche la vue admin/index
        $this->render('admin/index', ['produits' => $produits, 'success' => $success, 'error' => $error]);
    }

    // Page de modification d'un produit
    public function edit($id)
    {
        $productModel = new Product();
        $produit = $productModel->findById($id);

        if (!$produit) {
            $this->redirect('index.php?controller=admin&action=index');
        }

        // Traitement de la mise à jour
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier'])) {
            $nom = $_POST['produitNom'];
            $prix = $_POST['prix'];
            $desc = $_POST['descrip'];
            $categorie = $_POST['categorie'];
            $img = null;

            // Si une nouvelle image est fournie
            if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $dossier = ROOT_DIR . 'public/uploads/';
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $nomFichier = uniqid() . '_' . time() . '.' . $extension;
                
                $types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if(in_array(strtolower($extension), $types)) {
                    if(move_uploaded_file($_FILES['image']['tmp_name'], $dossier . $nomFichier)) {
                        $img = $nomFichier;
                    }
                }
            }

            // Met à jour en base via le modèle
            if($productModel->update($id, $nom, $desc, $prix, $img, $categorie)) {
                $this->redirect('index.php?controller=admin&action=index');
            } else {
                $error = "Erreur lors de la modification";
                $this->render('admin/edit', ['produit' => $produit, 'error' => $error]);
            }
        } else {
            $this->render('admin/edit', ['produit' => $produit]);
        }
    }

    // Action de suppression directe
    public function delete($id) 
    {
        if ($id) {
            $productModel = new Product();
            if($productModel->delete($id)) {
                 $this->redirect('index.php?controller=admin&action=index');
            } else {
                 $this->redirect('index.php?controller=admin&action=index');
            }
        }
        $this->redirect('index.php?controller=admin&action=index');
    }

    // Liste des commandes (gestion back-office)
    public function orders()
    {
        require_once __DIR__ . '/../models/Order.php';
        $orderModel = new Order();
        $commandes = $orderModel->findAll();
        
        $this->render('admin/orders', ['commandes' => $commandes]);
    }
}
