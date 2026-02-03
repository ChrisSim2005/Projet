<?php require __DIR__ . '/../layout/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>css/details.css?v=<?= time(); ?>">

<section class="detail-produit">
    <div class="container">
        <!-- Image du produit -->
        <div class="image-detail">
            <?php if(!empty($produit['img']) && file_exists(ROOT_DIR . 'public/uploads/' . $produit['img'])): ?>
                <img src="<?= BASE_URL ?>uploads/<?php echo htmlspecialchars($produit['img']); ?>" 
                     alt="<?php echo htmlspecialchars($produit['produitNom']); ?>">
            <?php else: ?>
                <img src="https://via.placeholder.com/600x600?text=Image+non+disponible" 
                     alt="Image non disponible">
            <?php endif; ?>
        </div>

        <!-- Informations du produit -->
        <div class="info-detail">
            <h1 class="nom-produit"><?php echo htmlspecialchars($produit['produitNom']); ?></h1>
            <div class="prix-produit"><?php echo number_format($produit['prix'], 0, ',', ' '); ?> Fcfa</div>
            
            <div class="description-complete">
                <h3>Description</h3>
                <p><?php echo nl2br(htmlspecialchars($produit['descrip'])); ?></p>
            </div>

            <div class="actions-detail">
                <a href="<?= BASE_URL ?>index.php?controller=home" class="btn-retour-grand">Retour</a>
                <button class="btn-ajouter-grand" onclick="ajouterAuPanier(<?php echo $produit['id']; ?>)">
                    Ajouter au panier
                </button>
            </div>
        </div>
    </div>
</section>

<script>
function ajouterAuPanier(idProduit) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '<?= BASE_URL ?>index.php?controller=cart&action=add', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    alert('Produit ajouté au panier avec succès!');
                    // Mettre à jour le badge du panier si existant dans le header
                    const badge = document.querySelector('.badge-panier');
                    if (badge) {
                        let count = parseInt(badge.textContent) || 0;
                        badge.textContent = count + 1;
                    }
                } else {
                    alert('Erreur: ' + response.message);
                }
            } catch(e) {
                console.error('Erreur parsing JSON:', e);
                alert('Fait ! Produit ajouté au panier.');
                location.reload();
            }
        }
    };
    xhr.send('id=' + idProduit);
}
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
