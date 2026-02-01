<?php require __DIR__ . '/../layout/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>css/admin.css?v=<?= time(); ?>">

<div class="admin-container">
    <h1 class="title">Modifier le produit</h1>

    <div class="card">
        <h2 style="display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-edit" style="color: var(--primary-yellow);"></i>
            Édition de #<?= $produit['id'] ?> : <?= htmlspecialchars($produit['produitNom']) ?>
        </h2>
        
        <form method="POST" enctype="multipart/form-data" action="<?= BASE_URL ?>index.php?controller=admin&action=edit&id=<?= $produit['id'] ?>">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <div class="form-group">
                    <label>Nom du produit</label>
                    <input type="text" name="produitNom" value="<?= htmlspecialchars($produit['produitNom']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Catégorie</label>
                    <select name="categorie" required>
                        <option value="">Sélectionner une catégorie</option>
                        <option value="Homme" <?= ($produit['categorie'] == 'Homme') ? 'selected' : '' ?>>Homme</option>
                        <option value="Femme" <?= ($produit['categorie'] == 'Femme') ? 'selected' : '' ?>>Femme</option>
                        <option value="Enfant" <?= ($produit['categorie'] == 'Enfant') ? 'selected' : '' ?>>Enfant</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>Prix (FCFA)</label>
                <input type="number" name="prix" step="0.01" value="<?= $produit['prix'] ?>" required>
            </div>
            
            <div class="form-group">
                <label>Description détaillée</label>
                <textarea name="descrip" rows="6"><?= htmlspecialchars($produit['descrip']) ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Image du produit</label>
                <div style="display: flex; gap: 20px; align-items: flex-start; margin-bottom: 15px; background: #252525; padding: 20px; border-radius: 12px; border: 1px solid #333;">
                    <?php if(!empty($produit['img']) && file_exists(ROOT_DIR . 'public/uploads/' . $produit['img'])): ?>
                        <div style="text-align: center;">
                            <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($produit['img']) ?>" alt="Aperçu" style="width: 120px; height: 120px; object-fit: cover; border-radius: 8px; border: 1px solid #444;">
                            <span style="display: block; font-size: 11px; color: var(--text-muted); margin-top: 5px;">Image Actuelle</span>
                        </div>
                    <?php endif; ?>
                    
                    <div style="flex-grow: 1;">
                        <input type="file" name="image" accept="image/*" style="margin-bottom: 10px;">
                        <p style="font-size: 13px; color: var(--text-muted);">
                            <i class="fas fa-info-circle"></i> Laissez vide pour conserver l'image actuelle.
                            <br>Formats supportés : JPG, PNG, WebP (Max 2MB).
                        </p>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 20px; margin-top: 40px; justify-content: flex-end;">
                <a href="<?= BASE_URL ?>index.php?controller=admin&action=index" style="text-decoration: none; padding: 15px 30px; border-radius: 10px; border: 1px solid #444; color: var(--text-muted); font-weight: 600;">
                    ANNULER
                </a>
                <button type="submit" name="modifier" class="btn-ajouter" style="padding: 15px 40px; border: none; border-radius: 10px; font-weight: 800; cursor: pointer;">
                    SAUVEGARDER LES MODIFICATIONS
                </button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
