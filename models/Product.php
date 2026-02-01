<?php // Ouverture de la balise PHP

require_once __DIR__ . '/../core/Database.php'; // Inclusion du fichier de base de données (Singleton)

class Product // Définition de la classe Product (Modèle)
{ // Ouverture de la classe
    private $pdo; // Propriété privée pour stocker l'instance PDO de connexion

    // Initialise la connexion via le Singleton Database
    public function __construct() // Constructeur de la classe
    { // Ouverture du constructeur
        $this->pdo = Database::getInstance()->getConnection(); // Récupération de l'unique connexion active
    } // Fin du constructeur

    // Récupère tous les produits (optionnellement par catégorie)
    public function findAll($category = null) // Méthode de sélection globale
    { // Ouverture de la méthode
        try { // Bloc de sécurité pour les erreurs SQL
            if ($category) { // Si un filtre de catégorie est spécifié
                $stmt = $this->pdo->prepare("SELECT * FROM produits WHERE categorie = ? ORDER BY id DESC"); // Préparation de la requête filtrée
                $stmt->execute([$category]); // Exécution avec le paramètre de catégorie
            } else { // Si aucun filtre n'est présent
                $stmt = $this->pdo->query("SELECT * FROM produits ORDER BY id DESC"); // Exécution directe pour tous les produits
                $stmt->execute(); // Confirmation d'exécution
            } // Fin de condition de filtre
            return $stmt->fetchAll(); // Retourne l'ensemble des résultats sous forme de tableau
        } catch (PDOException $e) { // Capture d'erreur PDO
            die("Erreur : " . $e->getMessage()); // Arrêt du script avec détail de l'erreur
        } // Fin du bloc try-catch
    } // Fin de la méthode findAll

    // Trouve un produit précis par son ID unique
    public function findById($id) // Méthode de recherche par ID
    { // Ouverture de la méthode
        try { // Début de protection
            $stmt = $this->pdo->prepare("SELECT * FROM produits WHERE id = :id"); // Préparation de la sélection par ID
            $stmt->execute(['id' => $id]); // Exécution protégée contre les injections
            return $stmt->fetch(); // Retourne l'unique ligne trouvée (ou false)
        } catch (PDOException $e) { // Capture d'erreur
            die("Erreur : " . $e->getMessage()); // Affichage d'erreur et arrêt
        } // Fin du bloc
    } // Fin de la méthode findById

    // Crée un nouveau produit dans la table SQL
    public function create($nom, $desc, $prix, $img, $categorie) // Méthode d'insertion
    { // Ouverture de la méthode
        try { // Début protection
            $stmt = $this->pdo->prepare("INSERT INTO produits (produitNom, descrip, prix, img, categorie) VALUES (?, ?, ?, ?, ?)"); // Requête préparée d'insertion
            return $stmt->execute([$nom, $desc, $prix, $img, $categorie]); // Retourne le résultat de l'exécution (succès/échec)
        } catch (PDOException $e) { // Capture d'erreur SQL
            return false; // Retourne faux en cas d'échec
        } // Fin du bloc
    } // Fin de la méthode create

    // Met à jour les infos d'un produit existant
    public function update($id, $nom, $desc, $prix, $img, $categorie) // Méthode de modification
    { // Ouverture de la méthode
        try { // Début protection
            if ($img) { // Si une nouvelle image a été fournie
                $stmt = $this->pdo->prepare("UPDATE produits SET produitNom = ?, descrip = ?, prix = ?, img = ?, categorie = ? WHERE id = ?"); // Mise à jour complète incluant l'image
                return $stmt->execute([$nom, $desc, $prix, $img, $categorie, $id]); // Exécution avec tous les champs
            } else { // Si l'ancienne image est conservée
                $stmt = $this->pdo->prepare("UPDATE produits SET produitNom = ?, descrip = ?, prix = ?, categorie = ? WHERE id = ?"); // Mise à jour sans modifier l'image
                return $stmt->execute([$nom, $desc, $prix, $categorie, $id]); // Exécution partielle
            } // Fin condition image
        } catch (PDOException $e) { // Capture d'erreur
            return false; // Échec
        } // Fin bloc
    } // Fin de la méthode update

    // Supprime un produit et son fichier image associé
    public function delete($id) // Méthode de suppression
    { // Ouverture de la méthode
        try { // Début protection
            // Récupère le nom du fichier image pour le supprimer du disque
            $stmt = $this->pdo->prepare("SELECT img FROM produits WHERE id = ?"); // Recherche de l'image liée
            $stmt->execute([$id]); // Exécution recherche
            $produit = $stmt->fetch(); // Récupération des données
            
            if($produit && !empty($produit['img'])) { // Si une image existe
                $chemin_image = ROOT_DIR . 'public/uploads/' . $produit['img']; // Construction du chemin absolu
                if(file_exists($chemin_image)) { // Vérification de la présence sur le serveur
                    unlink($chemin_image); // Suppression physique du fichier
                } // Fin test existence fichier
            } // Fin test image

            // Supprime la ligne dans la base de données
            $stmt = $this->pdo->prepare("DELETE FROM produits WHERE id = ?"); // Préparation de la suppression SQL
            return $stmt->execute([$id]); // Exécution finale
        } catch (PDOException $e) { // Capture d'erreur
            return false; // Échec
        } // Fin bloc
    } // Fin de la méthode delete
} // Fin de la classe

