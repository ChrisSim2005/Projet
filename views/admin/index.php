<?php require __DIR__ . '/../layout/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>css/admin.css?v=<?= time(); ?>">

<div class="admin-container">
    <h1 class="title">Gestion des produits</h1>

    <!-- Messages -->
    <div class="messages">
        <?php if(isset($success) && $success): ?>
            <div class="alert" style="background: rgba(39, 174, 96, 0.1); color: #2ecc71; padding: 20px; border-radius: 12px; margin-bottom: 30px; border: 1px solid rgba(39, 174, 96, 0.2); text-align: center;">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($error) && $error): ?>
            <div class="alert" style="background: rgba(231, 76, 60, 0.1); color: var(--accent-red); padding: 20px; border-radius: 12px; margin-bottom: 30px; border: 1px solid rgba(231, 76, 60, 0.2); text-align: center;">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
    </div>

    <div style="display: flex; gap: 30px; margin-bottom: 40px; justify-content: center;">
        <a href="<?= BASE_URL ?>index.php?controller=admin&action=orders" class="btn-ajouter" style="text-decoration: none; padding: 15px 30px; border-radius: 10px; display: inline-flex; align-items: center; gap: 10px;">
            <i class="fas fa-shopping-cart"></i> Gérer les Commandes
        </a>
    </div>

    <!-- Formulaire d'ajout -->
    <div class="card add-product">
        <h2><i class="fas fa-plus-circle" style="color: var(--primary-yellow); margin-right: 10px;"></i> Ajouter un nouveau produit</h2>
        <form method="POST" enctype="multipart/form-data" action="<?= BASE_URL ?>index.php?controller=admin&action=index">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Nom du produit</label>
                    <input type="text" placeholder="Ex: Habit de Luxe" name="produitNom" required>
                </div>

                <div class="form-group">
                    <label>Catégorie</label>
                    <select name="categorie" required>
                        <option value="">Choisir...</option>
                        <option value="Homme">Homme</option>
                        <option value="Femme">Femme</option>
                        <option value="Enfant">Enfant</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>Prix (FCFA)</label>
                <input type="number" placeholder="0" name="prix" step="0.01" required>
            </div>
            
            <div class="form-group">
                <label>Description</label>
                <textarea placeholder="Détails du produit..." name="descrip" rows="3"></textarea>   
            </div>
            
            <div class="form-group">
                <label>Image du produit</label>
                <input type="file" name="image" accept="image/*">
                <small>Format recommandé : Carré (800x800px). Max 2MB.</small>
            </div>
            
            <div style="text-align: right;">
                <button type="submit" name="ajouter" class="btn-ajouter" style="padding: 15px 40px; border: none; border-radius: 10px; font-weight: 800; cursor: pointer;">
                    CRÉER LE PRODUIT
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des produits -->
    <h2 style="font-size: 24px; font-weight: 800; margin: 60px 0 30px; text-transform: uppercase; text-align: center;">Catalogue produits</h2>
    
    <div class="card" style="padding: 0;">
        <?php if(isset($produits) && !empty($produits)): ?>
            <?php foreach($produits as $produit): ?>
            <div class="product-item" style="border-bottom: 1px solid #222;">
                <div class="product-image-container">
                    <?php if(!empty($produit['img']) && file_exists(ROOT_DIR . 'public/uploads/' . $produit['img'])): ?>
                        <img src="<?= BASE_URL ?>uploads/<?php echo htmlspecialchars($produit['img']); ?>" 
                             alt="<?php echo htmlspecialchars($produit['produitNom']); ?>"
                             class="product-image" style="width:100px; height:100px; object-fit:cover;">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/100?text=No+Image" 
                             alt="N/A"
                             class="product-image" style="width:100px; height:100px; object-fit:cover;">
                    <?php endif; ?>
                </div>

                <div class="info">
                    <strong style="color: var(--text-light); text-transform: uppercase;"><?php echo htmlspecialchars($produit['produitNom']); ?></strong>
                    <span style="font-size: 12px; color: var(--primary-yellow); background: rgba(241, 196, 15, 0.1); padding: 2px 8px; border-radius: 4px; margin-bottom: 8px; display: inline-block;">
                        <?php echo htmlspecialchars($produit['categorie']); ?>
                    </span>
                    <p class="desc"><?php echo htmlspecialchars(substr($produit['descrip'], 0, 80)); ?>...</p>
                    <p class="price"><?php echo number_format($produit['prix'], 0, ',', ' '); ?> F</p>
                </div>

                <div class="actions" style="display: flex; flex-direction: column; gap: 10px;">
                    <a href="<?= BASE_URL ?>index.php?controller=admin&action=edit&id=<?php echo $produit['id']; ?>" 
                       class="btn-edit" style="text-decoration: none; text-align: center; color: white;">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <a href="<?= BASE_URL ?>index.php?controller=admin&action=delete&id=<?php echo $produit['id']; ?>" 
                       class="btn-delete" 
                       onclick="return confirm('Supprimer ce produit définitivement ?')"
                       style="text-decoration: none; text-align: center; color: white;">
                        <i class="fas fa-trash"></i> Supprimer
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="padding: 60px; text-align: center; color: var(--text-muted);">
                <i class="fas fa-box-open" style="font-size: 48px; margin-bottom: 20px; color: #333;"></i>
                <p>Aucun produit dans le catalogue.</p>
            </div>
        <?php endif; ?>
    </div>

    <div style="margin-top: 60px; text-align: center;">
        <a href="<?= BASE_URL ?>index.php?controller=home" style="color: var(--text-muted); text-decoration: none; font-weight: 600;">
            <i class="fas fa-arrow-left"></i> Quitter l'administration
        </a>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
