<?php
session_start();
require '../controllers/connexionBDD.php';

// RÉCUPÉRER TOUS LES PRODUITS
try {
    $req = $pdo->query("SELECT * FROM produits ORDER BY id DESC");
    $produits = $req->fetchAll();
} catch(PDOException $e) {
    $erreur = "Erreur de chargement des produits: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Boutique E-commerce</title>
    <style>
        /* Style pour le bouton admin */
        .btn-admin {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            color: white;
            padding: 10px 25px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 10px rgba(155, 89, 182, 0.2);
            margin-right: 15px;
        }
        
        .btn-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(155, 89, 182, 0.3);
            background: linear-gradient(135deg, #8e44ad 0%, #7d3c98 100%);
        }
        
        .btn-admin i {
            font-size: 18px;
        }
        
        /* Style pour les boutons admin dans le header */
        .admin-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .btn-inscrire {
            background: linear-gradient(135deg, #27ae60 0%, #219653 100%);
            color: white;
            padding: 10px 25px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 10px rgba(39, 174, 96, 0.2);
        }
        
        .btn-inscrire:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(39, 174, 96, 0.3);
            background: linear-gradient(135deg, #219653 0%, #1e8449 100%);
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <nav>
            <h1>E-commerce</h1>
            <div class="admin-actions">
                <!-- Bouton Admin (visible seulement si admin est connecté) -->
                <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
                    <a href="admin.php" class="btn-admin">
                        <i class="fas fa-cog"></i> Administration
                    </a>
                <?php endif; ?>
                
                <!-- Bouton Connexion/Déconnexion -->
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="deconnexion.php" class="btn-admin" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </a>
                <?php else: ?>

                    <a href="inscription.php" class="btn-inscrire">
                        <i class="fas fa-sign-in-alt"></i> S'inscrire
                    </a>
                    
                    <a href="connexion.php" class="btn-admin" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);">
                        <i class="fas fa-sign-in-alt"></i> Connexion
                    </a>

                <?php endif; ?>
                
                <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'client'): ?>
                    <a href="panier.php">
                        <div class="panier">
                            <img src="../image/icon1.jpg" alt="Panier" width="70%">
                            <?php if(isset($_SESSION['panier']) && count($_SESSION['panier']) > 0): ?>
                                <span class="badge-panier"><?php echo count($_SESSION['panier']); ?></span>
                            <?php endif; ?>
                         </div>
                    </a>
                <?php endif ?>


            </div>
        </nav>
    </header>

    <section class="sgrille">
        <?php if(isset($erreur)): ?>
            <div style="color: red; text-align: center; padding: 20px;">
                <?php echo $erreur; ?>
            </div>
        <?php elseif(empty($produits)): ?>
            <div style="text-align: center; padding: 40px;">
                <h3>Aucun produit disponible pour le moment.</h3>
            </div>
        <?php else: ?>
            <div class="grille">
                <?php foreach($produits as $produit): ?>
                <div class="produit">
                    <div class="image">
                        <?php if(!empty($produit['img']) && file_exists('../uploads/' . $produit['img'])): ?>
                            <img src="../uploads/<?php echo htmlspecialchars($produit['img']); ?>" 
                                 alt="<?php echo htmlspecialchars($produit['produitNom']); ?>"
                                 width="60%">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/300x300?text=Image+non+disponible" 
                                 alt="Image non disponible"
                                 width="60%">
                        <?php endif; ?>
                    </div>
                    <br>
                    <div class="texte">
                        <h4 class="produit_nom">
                            <?php echo htmlspecialchars($produit['produitNom'] ?? 'Nom non disponible'); ?>
                        </h4>
                        <p class="description">
                            <?php 
                            $description = $produit['descrip'] ?? '';
                            if(!empty($description)) {
                                if(strlen($description) > 200) {
                                    echo htmlspecialchars(substr($description, 0, 200)) . '...';
                                } else {
                                    echo htmlspecialchars($description);
                                }
                            } else {
                                echo 'Aucune description disponible';
                            }
                            ?>
                        </p>
                        <h5 class="prix">
                            <?php echo number_format($produit['prix'] ?? 0, 0, ',', ' '); ?> Fcfa
                        </h5>
                    </div>
                    <br>
                    <div class="bouton">
                        <a href="details.php?id=<?php echo $produit['id']; ?>" class="details">Détails</a>
                        <button class="ajouter" onclick="ajouterAuPanier(<?php echo $produit['id']; ?>)">
                            Ajouter au panier
                        </button>  
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <script>
    function ajouterAuPanier(idProduit) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'ajouter_panier.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        alert('Produit ajouté au panier avec succès!');
                        // Mettre à jour le badge du panier
                        const badge = document.querySelector('.badge-panier');
                        if (badge) {
                            let count = parseInt(badge.textContent) || 0;
                            badge.textContent = count + 1;
                        } else {
                            // Créer le badge s'il n'existe pas
                            const panierDiv = document.querySelector('.panier');
                            if (panierDiv) {
                                const newBadge = document.createElement('span');
                                newBadge.className = 'badge-panier';
                                newBadge.textContent = '1';
                                panierDiv.appendChild(newBadge);
                            }
                        }
                    } else {
                        alert('Erreur: ' + response.message);
                    }
                } catch(e) {
                    console.error('Erreur parsing JSON:', e);
                    alert('Réponse invalide du serveur');
                }
            } else {
                alert('Erreur serveur: ' + xhr.status);
            }
        };
        
        xhr.onerror = function() {
            alert('Erreur réseau');
        };
        
        xhr.send('id=' + idProduit + '&action=ajouter');
    }
    </script>
</body>
</html>