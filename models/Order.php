<?php // Ouverture de la balise PHP

require_once __DIR__ . '/../core/Database.php'; // Inclusion de la classe de connexion à la base de données

class Order // Définition de la classe Order (Modèle) pour la gestion des commandes
{ // Ouverture de la classe
    private $pdo; // Propriété privée pour stocker l'objet PDO

    public function __construct() // Constructeur de la classe
    { // Ouverture du constructeur
        $this->pdo = Database::getInstance()->getConnection(); // Récupération de la connexion via le Singleton Database
    } // Fin du constructeur

    public function create($clientData, $items, $total) // Méthode de création d'une commande complète
    { // Ouverture de la méthode
        try { // Début de protection des transactions SQL
            $this->pdo->beginTransaction(); // Début d'une transaction pour garantir l'intégrité des données

            // 1. Enregistrer les informations du client
            $req_client = $this->pdo->prepare("INSERT INTO clients (nom, email, telephone, adresse, ville) 
                                        VALUES (?, ?, ?, ?, ?)"); // Préparation de l'insertion client
            $req_client->execute([ // Exécution de l'insertion avec les données nettoyées
                $clientData['nom'], // Nom du client
                $clientData['email'], // Email du client
                $clientData['telephone'], // Téléphone du client
                $clientData['adresse'], // Adresse physique
                $clientData['ville'] // Ville
            ]); // Fin exécution client
            $client_id = $this->pdo->lastInsertId(); // Récupération de l'ID généré pour le nouveau client
            
            // 2. Enregistrer l'en-tête de la commande
            $req_commande = $this->pdo->prepare("INSERT INTO commandes (client_id, total, methode_paiement, statut) 
                                          VALUES (?, ?, ?, 'en_attente')"); // Préparation de l'insertion commande
            $req_commande->execute([$client_id, $total, $clientData['methode_paiement']]); // Liaison avec le client et le montant total
            $commande_id = $this->pdo->lastInsertId(); // Récupération de l'ID de la nouvelle commande
            
            // 3. Enregistrer les lignes de détails de la commande (produits achetés)
            foreach($items as $item) { // Boucle de parcours des articles du panier
                // $item contient les données produit + quantité + sous-total
                $req_details = $this->pdo->prepare("INSERT INTO details_commande (commande_id, produit_id, quantite, prix_unitaire) 
                                             VALUES (?, ?, ?, ?)"); // Préparation de l'insertion du détail
                $req_details->execute([$commande_id, $item['id'], $item['qty'], $item['prix']]); // Insertion de chaque ligne article
            } // Fin de la boucle des détails

            $this->pdo->commit(); // Validation finale de tous les changements en base de données
            return $commande_id; // Retourne l'ID de la commande créée avec succès

        } catch(PDOException $e) { // Capture d'erreur SQL durant le processus
            $this->pdo->rollBack(); // Annulation de tous les changements en cas d'erreur (Atomicité)
            // Temporairement pour le debug
            die("Erreur base de données : " . $e->getMessage()); // Affichage technique et arrêt du script
            return false; // Retourne faux pour indiquer l'échec
        } // Fin du bloc transactionnel
    } // Fin de la méthode create

    public function findAll() // Méthode pour lister toutes les commandes (Admin)
    { // Ouverture de la méthode
        try { // Début protection
            // Construction de la requête SQL avec jointure pour récupérer le nom du client
            $sql = "SELECT c.*, cl.nom as client_nom, cl.email, cl.telephone, cl.ville 
                    FROM commandes c
                    JOIN clients cl ON c.client_id = cl.id
                    ORDER BY c.id DESC"; // Tri par ID décroissant (plus récentes en premier)

            $stmt = $this->pdo->query($sql); // Exécution directe de la requête de sélection
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourne toutes les commandes trouvées sous forme de tableau
        } catch(PDOException $e) { // Capture d'erreur
            return []; // Retourne un tableau vide en cas d'échec SQL
        } // Fin du bloc
    } // Fin de la méthode findAll
} // Fin de la classe Order

