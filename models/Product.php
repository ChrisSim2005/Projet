<?php

require_once __DIR__ . '/../core/Database.php'; // Charge la connexion BDD

class Product
{
    private $pdo; // Stocke l'objet de connexion

    // Initialise la connexion via le Singleton Database
    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // Récupère tous les produits (optionnellement par catégorie)
    public function findAll($category = null)
    {
        try {
            if ($category) { // Si catégorie filtrée
                $stmt = $this->pdo->prepare("SELECT * FROM produits WHERE categorie = ? ORDER BY id DESC");
                $stmt->execute([$category]);
            } else { // Sinon tous les produits
                $stmt = $this->pdo->query("SELECT * FROM produits ORDER BY id DESC");
                $stmt->execute();
            }
            return $stmt->fetchAll(); // Retourne la liste
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage()); // Stop si erreur BDD
        }
    }

    // Trouve un produit précis par son ID unique
    public function findById($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM produits WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(); // Retourne un seul produit
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
    }

    // Crée un nouveau produit dans la table SQL
    public function create($nom, $desc, $prix, $img, $categorie)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO produits (produitNom, descrip, prix, img, categorie) VALUES (?, ?, ?, ?, ?)");
            return $stmt->execute([$nom, $desc, $prix, $img, $categorie]); // Exécute l'insertion
        } catch (PDOException $e) {
            return false; // Erreur SQL
        }
    }

    // Met à jour les infos d'un produit existant
    public function update($id, $nom, $desc, $prix, $img, $categorie)
    {
        try {
            if ($img) { // Si on a uploadé une nouvelle image
                $stmt = $this->pdo->prepare("UPDATE produits SET produitNom = ?, descrip = ?, prix = ?, img = ?, categorie = ? WHERE id = ?");
                return $stmt->execute([$nom, $desc, $prix, $img, $categorie, $id]);
            } else { // Si on garde l'image actuelle
                $stmt = $this->pdo->prepare("UPDATE produits SET produitNom = ?, descrip = ?, prix = ?, categorie = ? WHERE id = ?");
                return $stmt->execute([$nom, $desc, $prix, $categorie, $id]);
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    // Supprime un produit et son fichier image associé
    public function delete($id)
    {
        try {
            // Récupère le nom du fichier image pour le supprimer du disque
            $stmt = $this->pdo->prepare("SELECT img FROM produits WHERE id = ?");
            $stmt->execute([$id]);
            $produit = $stmt->fetch();
            
            if($produit && !empty($produit['img'])) {
                $chemin_image = ROOT_DIR . 'public/uploads/' . $produit['img'];
                if(file_exists($chemin_image)) {
                    unlink($chemin_image); // Supprime le fichier
                }
            }

            // Supprime la ligne dans la base de données
            $stmt = $this->pdo->prepare("DELETE FROM produits WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
