<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/panier.css">
    <title>Panier - E-commerce</title>
</head>
<body>
    <header>
        <nav>
            <h1>E-commerce</h1>
            <a href="index.php">
                <div class="retour">← Retour à la boutique</div>
            </a>
        </nav>
    </header>

    <section class="panier-container">
        <h2>Votre Panier</h2>
        
        <?php if(empty($_SESSION['panier'])): ?>
            <div class="panier-vide">
                <p>Votre panier est vide</p>
                <a href="index.php" class="btn-continuer">Continuer mes achats</a>
            </div>
        <?php else: ?>
            <div class="liste-panier">
                <?php 
                $total = 0;
                foreach($_SESSION['panier'] as $item): 
                    $sousTotal = $item['prix'] * $item['quantite'];
                    $total += $sousTotal;
                ?>
                <div class="item-panier" id="item-<?php echo $item['id']; ?>">
                    <div class="item-image">
                        <?php if(!empty($item['img']) && file_exists('../uploads/' . $item['img'])): ?>
                            <img src="../uploads/<?php echo htmlspecialchars($item['img']); ?>" 
                                 alt="<?php echo htmlspecialchars($item['produitNom']); ?>">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/80" alt="Image produit">
                        <?php endif; ?>
                    </div>
                    
                    <div class="item-info">
                        <h4><?php echo htmlspecialchars($item['produitNom']); ?></h4>
                        <p class="prix-unitaire"><?php echo number_format($item['prix'], 0, ',', ' '); ?> Fcfa l'unité</p>
                        <div class="quantite">
                            <button onclick="modifierQuantite(<?php echo $item['id']; ?>, -1)">-</button>
                            <span class="qte"><?php echo $item['quantite']; ?></span>
                            <button onclick="modifierQuantite(<?php echo $item['id']; ?>, 1)">+</button>
                        </div>
                    </div>
                    
                    <div class="item-sous-total">
                        <p class="sous-total"><?php echo number_format($sousTotal, 0, ',', ' '); ?> Fcfa</p>
                        <button class="supprimer" onclick="supprimerDuPanier(<?php echo $item['id']; ?>)">
                            Supprimer
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="total-panier">
                <div class="total-info">
                    <p>Sous-total: <span><?php echo number_format($total, 0, ',', ' '); ?> Fcfa</span></p>
                    <p>Livraison: <span>À calculer</span></p>
                    <h3>Total: <span class="total-final"><?php echo number_format($total, 0, ',', ' '); ?> Fcfa</span></h3>
                </div>
                
                <div class="actions-panier">
                    <a href="index.php" class="btn-continuer">← Continuer mes achats</a>
                    <a href="commande.php" class="btn-commander">Passer la commande</a>
                </div>
            </div>
        <?php endif; ?>
    </section>

    <script>
    function modifierQuantite(idProduit, changement) {
        fetch('ajouter_panier.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + idProduit + '&action=' + (changement > 0 ? 'ajouter' : 'supprimer_un')
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue');
        });
    }
    
    function supprimerDuPanier(idProduit) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce produit du panier ?')) {
            fetch('ajouter_panier.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + idProduit + '&action=supprimer'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Supprimer l'élément du DOM
                    const item = document.getElementById('item-' + idProduit);
                    if (item) {
                        item.style.opacity = '0';
                        item.style.transform = 'translateX(-20px)';
                        setTimeout(() => {
                            location.reload();
                        }, 300);
                    } else {
                        location.reload();
                    }
                } else {
                    alert('Erreur: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue');
            });
        }
    }
    
    
    </script>
</body>
</html>