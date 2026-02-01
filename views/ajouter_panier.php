<?php
session_start();
require '../controllers/connexionBDD.php';

// Vérifier la méthode de requête
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && isset($_POST['action'])) {
        $id = intval($_POST['id']);
        $action = $_POST['action'];
        
        // Initialiser le panier s'il n'existe pas
        if (!isset($_SESSION['panier'])) {
            $_SESSION['panier'] = [];
        }
        
        // Récupérer les informations du produit
        try {
            $req = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
            $req->execute([$id]);
            $produit = $req->fetch();
            
            if ($produit) {
                if ($action === 'ajouter') {
                    // Vérifier si le produit est déjà dans le panier
                    $produitExiste = false;
                    foreach ($_SESSION['panier'] as &$item) {
                        if ($item['id'] == $id) {
                            $item['quantite']++;
                            $produitExiste = true;
                            break;
                        }
                    }
                    
                    // Si le produit n'existe pas encore, l'ajouter
                    if (!$produitExiste) {
                        $_SESSION['panier'][] = [
                            'id' => $produit['id'],
                            'produitNom' => $produit['produitNom'],
                            'prix' => $produit['prix'],
                            'img' => $produit['img'],
                            'quantite' => 1
                        ];
                    }
                    
                    echo json_encode(['success' => true, 'message' => 'Produit ajouté au panier']);
                    
                } elseif ($action === 'supprimer') {
                    // Supprimer le produit du panier
                    foreach ($_SESSION['panier'] as $key => $item) {
                        if ($item['id'] == $id) {
                            unset($_SESSION['panier'][$key]);
                            $_SESSION['panier'] = array_values($_SESSION['panier']); // Réindexer
                            break;
                        }
                    }
                    
                    echo json_encode(['success' => true, 'message' => 'Produit supprimé du panier']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Produit non trouvé']);
            }
            
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
}
?>