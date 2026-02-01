<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Product.php';

class ProductController extends Controller
{
    public function details($id)
    {
        if (empty($id)) {
            $this->redirect('index.php');
        }

        $productModel = new Product();
        $produit = $productModel->findById($id);

        if (!$produit) {
            // Rediriger vers l'accueil ou afficher une erreur
             $this->redirect('index.php');
        }

        $this->render('details', ['produit' => $produit]);
    }
}
