<?php require __DIR__ . '/../layout/header.php'; ?> <!-- Inclut l'en-tête commun -->

<!-- Section des filtres par catégorie -->
<section class="filtres-produits" style="display: flex; justify-content: center; gap: 15px; margin: 20px 0;">
    <!-- Lien pour afficher tous les produits -->
    <a href="<?= BASE_URL ?>index.php?controller=home" class="btn" style="background: #34495e; color: white; text-decoration: none; padding: 10px 20px; border-radius: 20px;">Tout</a>
    <!-- Liens filtrés par catégorie (couleur or Shopkip) -->
    <a href="<?= BASE_URL ?>index.php?controller=home&category=Homme" class="btn" style="background: #d4ac0d; color: white; text-decoration: none; padding: 10px 20px; border-radius: 20px;">Homme</a>
    <a href="<?= BASE_URL ?>index.php?controller=home&category=Femme" class="btn" style="background: #d4ac0d; color: white; text-decoration: none; padding: 10px 20px; border-radius: 20px;">Femme</a>
    <a href="<?= BASE_URL ?>index.php?controller=home&category=Enfant" class="btn" style="background: #d4ac0d; color: white; text-decoration: none; padding: 10px 20px; border-radius: 20px;">Enfant</a>
</section>

<!-- Section principale de la grille de produits -->
<section class="sgrille">
    <?php if(empty($produits)): ?>
        <!-- Message si aucun produit n'est trouvé -->
        <div style="text-align: center; padding: 40px;">
            <h3>Aucun produit disponible pour le moment.</h3>
        </div>
    <?php else: ?>
        <!-- Grille d'affichage des produits -->
        <div class="grille">
            <?php foreach($produits as $produit): ?>
            <div class="produit">
                <div class="image">
                    <!-- Vérifie si l'image existe physiquement sur le serveur -->
                    <?php if(!empty($produit['img']) && file_exists(ROOT_DIR . 'public/uploads/' . $produit['img'])): ?>
                        <!-- Affiche l'image du produit -->
                        <img src="<?= BASE_URL ?>uploads/<?php echo htmlspecialchars($produit['img']); ?>" 
                             alt="<?php echo htmlspecialchars($produit['produitNom']); ?>"
                             width="60%">
                    <?php else: ?>
                        <!-- Image de remplacement si non trouvée -->
                        <img src="https://via.placeholder.com/300x300?text=Image+non+disponible" 
                             alt="Image non disponible"
                             width="60%">
                    <?php endif; ?>
                </div>
                <br>
                <div class="texte">
                    <!-- Nom du produit -->
                    <h4 class="produit_nom">
                        <?php echo htmlspecialchars($produit['produitNom'] ?? 'Nom non disponible'); ?>
                    </h4>
                    <!-- Troncation de la description à 200 caractères -->
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
                    <!-- Prix formaté -->
                    <h5 class="prix">
                        <?php echo number_format($produit['prix'] ?? 0, 0, ',', ' '); ?> Fcfa
                    </h5>
                </div>
                <br>
                <!-- Boutons d'action -->
                <div class="bouton">
                    <!-- Lien vers la page de détails -->
                    <a href="<?= BASE_URL ?>index.php?controller=product&action=details&id=<?php echo $produit['id']; ?>" class="details">Détails</a>
                    <!-- Bouton pour ajouter au panier (appel JS) -->
                    <a href="<?= BASE_URL ?>index.php?controller=cart&action=add&id=<?php echo $produit['id']; ?>" 
                       class="btn-ajouter" 
                       onclick="event.preventDefault(); ajouterAuPanier(<?php echo $produit['id']; ?>);">
                        Ajouter
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/../layout/footer.php'; ?> <!-- Inclut le pied de page commun -->
