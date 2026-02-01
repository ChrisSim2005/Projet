<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/Product.php';

class CartController extends Controller
{
    public function index()
    {
        // Affichage du panier
        $cartModel = new Cart();
        $items = $cartModel->getItems();
        
        $productModel = new Product();
        $productsInCart = [];
        $total = 0;

        foreach ($items as $id => $qty) {
            $product = $productModel->findById($id);
            if ($product) {
                $product['qty'] = $qty; // On ajoute la quantité au tableau produit pour la vue
                $productsInCart[] = $product;
                $total += $product['prix'] * $qty; // Pas de qty gérée dans add() pour l'instant (toujours 1 au clic ds l'original), mais on prévoit. 
                // Note : le script JS original fait 'ajouter_panier.php' qui fait $_SESSION['panier'][] = $id (array simple ou assoc?)
                // Vérifions l'original : 
                // Original 'ajouter_panier.php' fait: array_push($_SESSION['panier'], $_POST['id']);
                // Donc le panier original est une liste d'IDs [1, 2, 1, 3].
                // Ma classe Cart fait un tableau associatif [id => qty].
                // C'est une amélioration. Je dois m'assurer que l'affichage suit.
            }
        }

        $this->render('cart/index', [
            'panier' => $productsInCart,
            'total' => $total
        ]);
    }

    public function add($id = null)
    {
        // On récupère l'ID soit du paramètre (GET/Router), soit du POST
        if (!$id) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : (isset($_GET['id']) ? intval($_GET['id']) : 0);
        } else {
            $id = intval($id);
        }
        
        if ($id > 0) {
            $cart = new Cart();
            $cart->add($id);
            
            // Si c'est une requête AJAX (XHR)
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Produit ajouté']);
                exit;
            } else {
                // Sinon redirection classique vers le panier
                $this->redirect('index.php?controller=cart&action=index');
            }
        }
        
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'ID invalide']);
            exit;
        } else {
            $this->redirect('index.php');
        }
    }

    public function remove($id)
    {
        if ($id) {
            $cart = new Cart();
            $cart->remove($id);
        }
        $this->redirect('index.php?controller=cart&action=index');
    }

    public function increase($id)
    {
        if ($id) {
            $cart = new Cart();
            $cart->add($id);
        }
        $this->redirect('index.php?controller=cart&action=index');
    }

    public function decrease($id)
    {
        if ($id) {
            $cart = new Cart();
            $cart->decrease($id);
        }
        $this->redirect('index.php?controller=cart&action=index');
    }

    public function checkout()
    {
        // 1. Récupérer les produits du panier
        require_once __DIR__ . '/../models/Cart.php';
        $cartModel = new Cart();
        $items = $cartModel->getItems();
        
        if (empty($items)) {
            $this->redirect('index.php?controller=cart&action=index');
        }

        require_once __DIR__ . '/../models/Product.php';
        $productModel = new Product();
        $productsInCart = [];
        $total = 0;

        foreach ($items as $id => $qty) {
            $product = $productModel->findById($id);
            if ($product) {
                $product['qty'] = $qty; 
                $productsInCart[] = $product;
                $total += $product['prix'] * $qty;
            }
        }

        // 2. Gérer la soumission du formulaire (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['passer_commande'])) {
            require_once __DIR__ . '/../models/Order.php';
            
            // Validation
            $erreurs = [];
            $nom = htmlspecialchars($_POST['nom'] ?? '');
            
            if(empty($nom)) $erreurs[] = "Le nom est requis";
            
            if (empty($erreurs)) {
                $orderModel = new Order();
                // Assurer que toutes les clés existent
                $data = $_POST;
                $orderId = $orderModel->create($data, $productsInCart, $total);
                
                if ($orderId) {
                    $cartModel->clear();
                    $_SESSION['commande_id'] = $orderId;
                    header('Location: index.php?controller=cart&action=confirmation'); 
                    exit;
                } else {
                    $erreur_bdd = "Erreur lors de l'enregistrement de la commande.";
                }
            }
        }

        // 3. Afficher la vue
        $this->render('cart/checkout', [
            'panier' => $productsInCart,
            'total' => $total,
            'erreurs' => $erreurs ?? [],
            'erreur_bdd' => $erreur_bdd ?? null
        ]);
    }

    public function confirmation()
    {
        if(!isset($_SESSION['commande_id'])) {
            $this->redirect('index.php');
        }

        $commande_id = $_SESSION['commande_id'];
        unset($_SESSION['commande_id']);
        
        $this->render('cart/confirmation');
    }
}
