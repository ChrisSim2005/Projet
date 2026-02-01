<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Product.php';

class HomeController extends Controller
{
    public function index($category = null)
    {
        $productModel = new Product();
        // $category est automatiquement injecté par le Routeur via $_GET['category'] si présent
        $produits = $productModel->findAll($category);

        $this->render('home/index', ['produits' => $produits]);
    }
}
