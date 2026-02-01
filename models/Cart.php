<?php // Ouverture de la balise PHP

class Cart // Définition de la classe Cart pour gérer le panier en session
{ // Ouverture de la classe
    public function __construct() // Constructeur de la classe
    { // Ouverture du constructeur
        if (!isset($_SESSION['panier'])) { // Vérification si le panier existe dans la session
            $_SESSION['panier'] = []; // Initialisation d'un tableau vide si inexistant
        } // Fin de la vérification initiale
    } // Fin du constructeur

    public function add($productId, $quantity = 1) // Méthode pour ajouter un produit ou augmenter sa quantité
    { // Ouverture de la méthode add
        if (isset($_SESSION['panier'][$productId])) { // Si le produit est déjà présent dans le panier
            $_SESSION['panier'][$productId] += $quantity; // Incrémentation de la quantité existante
        } else { // Si c'est un nouveau produit pour le panier
            $_SESSION['panier'][$productId] = $quantity; // Création de l'entrée avec la quantité initiale
        } // Fin du test de présence
    } // Fin de la méthode add

    public function decrease($productId, $quantity = 1) // Méthode pour réduire la quantité d'un produit
    { // Ouverture de la méthode decrease
        if (isset($_SESSION['panier'][$productId])) { // Si le produit est bien présent
            $_SESSION['panier'][$productId] -= $quantity; // Décrémentation de la quantité
            if ($_SESSION['panier'][$productId] <= 0) { // Si la quantité tombe à zéro ou moins
                unset($_SESSION['panier'][$productId]); // Suppression totale du produit du panier
            } // Fin de vérification du stock minimal
        } // Fin de test de présence
    } // Fin de la méthode decrease

    public function remove($productId) // Méthode pour supprimer un produit sans tenir compte de sa quantité
    { // Ouverture de la méthode remove
        if (isset($_SESSION['panier'][$productId])) { // Si le produit existe dans le panier
            unset($_SESSION['panier'][$productId]); // Suppression de la clé correspondante
        } // Fin du test de présence
    } // Fin de la méthode remove

    public function getItems() // Méthode pour obtenir la liste brute des produits et quantités
    { // Ouverture de la méthode
        return $_SESSION['panier']; // Retourne le tableau associatif stocké en session
    } // Fin de la méthode getItems

    public function count() // Méthode pour compter le volume total d'articles
    { // Ouverture de la méthode
        return array_sum($_SESSION['panier']); // Retourne la somme de toutes les quantités (total d'objets)
    } // Fin de la méthode count
    
    public function clear() // Méthode pour vider intégralement le panier
    { // Ouverture de la méthode
        $_SESSION['panier'] = []; // Réinitialisation du tableau de session à vide
    } // Fin de la méthode clear
} // Fin de la classe Cart

