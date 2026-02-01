<?php

require_once __DIR__ . '/../core/Database.php';

class Order
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function create($clientData, $items, $total)
    {
        try {
            $this->pdo->beginTransaction();

            // 1. Enregistrer le client
            $req_client = $this->pdo->prepare("INSERT INTO clients (nom, email, telephone, adresse, ville) 
                                        VALUES (?, ?, ?, ?, ?)");
            $req_client->execute([
                $clientData['nom'], 
                $clientData['email'], 
                $clientData['telephone'], 
                $clientData['adresse'], 
                $clientData['ville']
            ]);
            $client_id = $this->pdo->lastInsertId();
            
            // 2. Enregistrer la commande
            $req_commande = $this->pdo->prepare("INSERT INTO commandes (client_id, total, methode_paiement, statut) 
                                          VALUES (?, ?, ?, 'en_attente')");
            $req_commande->execute([$client_id, $total, $clientData['methode_paiement']]);
            $commande_id = $this->pdo->lastInsertId();
            
            // 3. Enregistrer les détails de la commande
            foreach($items as $item) {
                // $item contains product data + qty + subtotal
                $req_details = $this->pdo->prepare("INSERT INTO details_commande (commande_id, produit_id, quantite, prix_unitaire) 
                                             VALUES (?, ?, ?, ?)");
                $req_details->execute([$commande_id, $item['id'], $item['qty'], $item['prix']]);
            }

            $this->pdo->commit();
            return $commande_id;

        } catch(PDOException $e) {
            $this->pdo->rollBack();
            // Temporairement pour le debug
            die("Erreur base de données : " . $e->getMessage());
            return false;
        }
    }
    public function findAll()
    {
        try {
            $sql = "SELECT c.*, cl.nom as client_nom, cl.email, cl.telephone, cl.ville 
                    FROM commandes c
                    JOIN clients cl ON c.client_id = cl.id
                    ORDER BY c.date_commande DESC"; 
            // Note: vérifier si la colonne date_commande existe, sinon utiliser c.id DESC ou ajoutez-la.
            // D'après le create(), on n'insert pas de date explicitement, donc ça dépend si il y a un current_timestamp par défaut.
            // On va supposer qu'il y a un ID auto-increment qu'on peut utiliser pour l'ordre.
            
            // Si la colonne date n'existe pas, on trie par ID
            $sql = "SELECT c.*, cl.nom as client_nom, cl.email, cl.telephone, cl.ville 
                    FROM commandes c
                    JOIN clients cl ON c.client_id = cl.id
                    ORDER BY c.id DESC";

            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }
}
