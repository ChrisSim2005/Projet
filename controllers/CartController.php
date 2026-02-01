<?php // Ouverture de la balise PHP

require_once __DIR__ . '/../core/Controller.php'; // Inclusion du contrôleur de base
require_once __DIR__ . '/../models/Cart.php'; // Inclusion du modèle Panier
require_once __DIR__ . '/../models/Product.php'; // Inclusion du modèle Produit

class CartController extends Controller // Définition de la classe CartController pour la gestion du panier
{ // Ouverture de la classe
    public function index() // Méthode pour afficher le contenu détaillé du panier
    { // Ouverture de la méthode index
        // Affichage du panier
        $cartModel = new Cart(); // Instanciation du modèle Cart
        $items = $cartModel->getItems(); // Récupération brute des articles (IDs et quantités) depuis la session
        
        $productModel = new Product(); // Instanciation du modèle Product pour les détails
        $productsInCart = []; // Initialisation de la liste des produits complète
        $total = 0; // Initialisation du montant total du panier

        foreach ($items as $id => $qty) { // Boucle sur chaque article présent dans le panier
            $product = $productModel->findById($id); // Récupération des infos du produit en base
            if ($product) { // Si le produit existe toujours en base
                $product['qty'] = $qty; // Injection de la quantité commandée dans le tableau produit
                $productsInCart[] = $product; // Ajout du produit enrichi à la liste finale
                $total += $product['prix'] * $qty; // Calcul et cumul du prix total pour cet article
            } // Fin de vérification produit
        } // Fin de la boucle de traitement du panier

        $this->render('cart/index', [ // Appel de la vue d'affichage du panier
            'panier' => $productsInCart, // Transmission de la liste des produits
            'total' => $total // Transmission du total calculé
        ]); // Fin rendu vue
    } // Fin méthode index

    public function add($id = null) // Méthode pour ajouter un produit au panier
    { // Ouverture de la méthode add
        // On récupère l'ID soit du paramètre (GET/Router), soit du POST
        if (!$id) { // Si l'ID n'est pas passé par l'URL
            $id = isset($_POST['id']) ? intval($_POST['id']) : (isset($_GET['id']) ? intval($_GET['id']) : 0); // Récupération sécurisée depuis POST ou GET
        } else { // Si l'ID est dans l'URL
            $id = intval($id); // Forçage en entier pour la sécurité
        } // Fin récupération ID
        
        if ($id > 0) { // Si l'ID est valide (> 0)
            $cart = new Cart(); // Instanciation du modèle Panier
            $cart->add($id); // Appel de la logique d'ajout (incrémentation)
            
            // Si c'est une requête AJAX (XHR) via JavaScript
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') { // Détection AJAX
                header('Content-Type: application/json'); // Définition du type de réponse JSON
                echo json_encode(['success' => true, 'message' => 'Produit ajouté']); // Envoi de la confirmation JSON
                exit; // Arrêt immédiat pour ne pas corrompre le JSON
            } else { // Si c'est un ajout via clic lien classique
                // Sinon redirection classique vers le panier
                $this->redirect('index.php?controller=cart&action=index'); // Redirection vers la vue panier
            } // Fin test AJAX
        } // Fin test ID valide
        
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') { // Cas d'échec AJAX
            header('Content-Type: application/json'); // Type JSON
            echo json_encode(['success' => false, 'message' => 'ID invalide']); // Message d'erreur JSON
            exit; // Arrêt
        } else { // Cas d'échec classique
            $this->redirect('index.php'); // Retour à l'accueil
        } // Fin traitement échec
    } // Fin méthode add

    public function remove($id) // Méthode pour supprimer totalement un article du panier
    { // Ouverture de la méthode
        if ($id) { // Si l'ID est fourni
            $cart = new Cart(); // Instance panier
            $cart->remove($id); // Suppression de la clé correspondante en session
        } // Fin test ID
        $this->redirect('index.php?controller=cart&action=index'); // Retour immédiat à la vue panier
    } // Fin méthode remove

    public function increase($id) // Méthode pour augmenter la quantité d'un produit (+1)
    { // Ouverture de la méthode
        if ($id) { // Si l'ID est présent
            $cart = new Cart(); // Instance panier
            $cart->add($id); // Réutilisation de add() qui incrémente la quantité
        } // Fin test ID
        $this->redirect('index.php?controller=cart&action=index'); // Rafraîchissement du panier
    } // Fin méthode increase

    public function decrease($id) // Méthode pour diminuer la quantité d'un produit (-1)
    { // Ouverture de la méthode
        if ($id) { // Si l'ID est présent
            $cart = new Cart(); // Instance panier
            $cart->decrease($id); // Appel de la logique de décrémentation
        } // Fin test ID
        $this->redirect('index.php?controller=cart&action=index'); // Rafraîchissement du panier
    } // Fin méthode decrease

    public function checkout() // Méthode pour gérer l'étape de validation de la commande (tunnel de paiement)
    { // Ouverture de la méthode checkout
        // 1. Récupérer les produits du panier
        require_once __DIR__ . '/../models/Cart.php'; // Inclusion modèle Panier
        $cartModel = new Cart(); // Instance panier
        $items = $cartModel->getItems(); // Récupération des items
        
        if (empty($items)) { // Si le panier est vide
            $this->redirect('index.php?controller=cart&action=index'); // Redirection vers le panier vide
        } // Fin test panier vide

        require_once __DIR__ . '/../models/Product.php'; // Inclusion modèle Produit
        $productModel = new Product(); // Instance produit
        $productsInCart = []; // Liste produits détaillée
        $total = 0; // Total commande

        foreach ($items as $id => $qty) { // Boucle de récupération des détails produits
            $product = $productModel->findById($id); // Recherche SQL
            if ($product) { // Si trouvé
                $product['qty'] = $qty; // Ajout qty
                $productsInCart[] = $product; // Stockage
                $total += $product['prix'] * $qty; // Calcul prix
            } // Fin test produit
        } // Fin boucle

        // 2. Gérer la soumission du formulaire de commande (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['passer_commande'])) { // Si formulaire validé
            require_once __DIR__ . '/../models/Order.php'; // Inclusion modèle Commande
            
            // Validation simple des données
            $erreurs = []; // Tableau d'erreurs
            $nom = htmlspecialchars($_POST['nom'] ?? ''); // Sécurisation du nom
            
            if(empty($nom)) $erreurs[] = "Le nom est requis"; // Vérification champ obligatoire
            
            if (empty($erreurs)) { // Si aucune erreur de saisie
                $orderModel = new Order(); // Instance commande
                // Assurer que toutes les clés existent pour l'insertion
                $data = $_POST; // Récupération brute des données postées
                $orderId = $orderModel->create($data, $productsInCart, $total); // Enregistrement SQL commande + détails
                
                if ($orderId) { // Si la commande est créée
                    $cartModel->clear(); // Vidage complet du panier en session
                    $_SESSION['commande_id'] = $orderId; // Stockage temporaire ID commande pour confirmation
                    header('Location: index.php?controller=cart&action=confirmation'); // Redirection vers succès
                    exit; // Arrêt script
                } else { // Si l'insertion SQL échoue
                    $erreur_bdd = "Erreur lors de l'enregistrement de la commande."; // Message d'erreur technique
                } // Fin test orderId
            } // Fin test erreurs
        } // Fin bloc POST

        // 3. Afficher la vue de paiement / checkout
        $this->render('cart/checkout', [ // Rendu vue terminale
            'panier' => $productsInCart, // Données panier
            'total' => $total, // Montant final
            'erreurs' => $erreurs ?? [], // Erreurs de saisie éventuelles
            'erreur_bdd' => $erreur_bdd ?? null // Erreur technique éventuelle
        ]); // Fin render
    } // Fin méthode checkout

    public function confirmation() // Méthode pour afficher la page de succès de commande
    { // Ouverture de la méthode
        if(!isset($_SESSION['commande_id'])) { // Sécurité : si on n'arrive pas de checkout
            $this->redirect('index.php'); // Retour accueil
        } // Fin sécurité

        $commande_id = $_SESSION['commande_id']; // Récupération de l'ID commande de session
        unset($_SESSION['commande_id']); // Suppression pour éviter les rechargements de page successifs
        
        $this->render('cart/confirmation'); // Affichage du message de confirmation
    } // Fin méthode confirmation
} // Fin de la classe CartController

