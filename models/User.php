<?php

require_once __DIR__ . '/../core/Database.php'; // Charge la connexion BDD

class User
{
    private $pdo; // Stocke l'objet PDO

    // Initialise la connexion via le Singleton Database
    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // Cherche un utilisateur par son adresse e-mail
    public function findByEmail($email)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM utilisateur WHERE email = :email");
            $stmt->execute(['email' => $email]);
            return $stmt->fetch(); // Retourne l'utilisateur ou false
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage()); // Stop si erreur SQL
        }
    }

    // Crée un nouvel utilisateur dans la table 'utilisateur'
    public function create($nom, $prenom, $email, $mdp, $telephone)
    {
        try {
            // Note: Les mots de passe sont stockés en clair pour compatibilité système
            $sql = "INSERT INTO utilisateur (nom, prenom, email, mdp, telephone) VALUES (:nom, :prenom, :email, :mdp, :telephone)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'mdp' => $mdp,
                'telephone' => $telephone
            ]); // Exécute l'insertion
        } catch (PDOException $e) {
            return false; // Échec de l'insertion
        }
    }

    // Trouve un utilisateur par son identifiant numérique unique
    public function findById($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM utilisateur WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(); // Retourne les infos
        } catch (PDOException $e) {
            return false;
        }
    }

    // Met à jour les informations de base du compte (Nom, Prénom)
    public function updateProfile($id, $nom, $prenom)
    {
        try {
            $sql = "UPDATE utilisateur SET nom = :nom, prenom = :prenom WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                'nom' => $nom,
                'prenom' => $prenom,
                'id' => $id
            ]); // Applique les changements
        } catch (PDOException $e) {
            return false;
        }
    }

    // Change le mot de passe de l'utilisateur
    public function updatePassword($id, $new_mdp)
    {
        try {
            // Mise à jour du MDP (en clair pour cohérence projet)
            $sql = "UPDATE utilisateur SET mdp = :mdp WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                'mdp' => $new_mdp,
                'id' => $id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
