<?php

class Cart
{
    public function __construct()
    {
        if (!isset($_SESSION['panier'])) {
            $_SESSION['panier'] = [];
        }
    }

    public function add($productId, $quantity = 1)
    {
        if (isset($_SESSION['panier'][$productId])) {
            $_SESSION['panier'][$productId] += $quantity;
        } else {
            $_SESSION['panier'][$productId] = $quantity;
        }
    }

    public function decrease($productId, $quantity = 1)
    {
        if (isset($_SESSION['panier'][$productId])) {
            $_SESSION['panier'][$productId] -= $quantity;
            if ($_SESSION['panier'][$productId] <= 0) {
                unset($_SESSION['panier'][$productId]);
            }
        }
    }

    public function remove($productId)
    {
        if (isset($_SESSION['panier'][$productId])) {
            unset($_SESSION['panier'][$productId]);
        }
    }

    public function getItems()
    {
        return $_SESSION['panier'];
    }

    public function count()
    {
        return array_sum($_SESSION['panier']); // Ou count($_SESSION['panier']) selon la logique voulue (types de produits ou qté totale)
        // Le code original utilisait count(), donc nombre de références produits.
    }
    
    public function clear()
    {
        $_SESSION['panier'] = [];
    }
}
