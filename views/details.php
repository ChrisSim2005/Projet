<?php require __DIR__ . '/layout/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>css/details.css?v=<?= time(); ?>">

<section class="detail-produit">
    <div class="container">
        <div class="image-detail">
            <?php if(!empty($produit['img']) && file_exists(ROOT_DIR . 'public/uploads/' . $produit['img'])): ?>
                <img src="<?= BASE_URL ?>uploads/<?php echo htmlspecialchars($produit['img']); ?>" 
                     alt="<?php echo htmlspecialchars($produit['produitNom']); ?>">
            <?php else: ?>
                <img src="https://via.placeholder.com/600x600?text=Image+non+disponible" 
                     alt="Image non disponible">
            <?php endif; ?>
        </div>
        
        <div class="info-detail">
            <h1 class="nom-produit"><?php echo htmlspecialchars($produit['produitNom']); ?></h1>
            <h2 class="prix-produit"><?php echo number_format($produit['prix'], 0, ',', ' '); ?> Fcfa</h2>
            
            <div class="description-complete">
                <h3>Description</h3>
                <p><?php echo nl2br(htmlspecialchars($produit['descrip'])); ?></p>
            </div>
            
            <div class="actions-detail">
                <a href="<?= BASE_URL ?>index.php?controller=cart&action=add&id=<?php echo $produit['id']; ?>" class="btn-ajouter-grand">
                    Ajouter au panier
                </a>
                <a href="<?= BASE_URL ?>index.php?controller=home" class="btn-retour-grand">Boutique</a>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/layout/footer.php'; ?>