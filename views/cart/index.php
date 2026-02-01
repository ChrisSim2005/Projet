<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="panier-container" style="padding: 60px 40px; max-width: 1200px; margin: 0 auto; min-height: 80vh;">
    <h2 style="font-size: 32px; font-weight: 800; margin-bottom: 30px; text-transform: uppercase; color: var(--text-light);">Votre Panier</h2>
    
    <?php if(empty($panier)): ?>
        <div style="background: var(--dark-card); padding: 50px; border-radius: 15px; text-align: center; border: 1px solid #222;">
            <p style="font-size: 18px; color: var(--text-muted); margin-bottom: 30px;">Votre panier est encore vide. Explorez nos produits !</p>
            <a href="<?= BASE_URL ?>index.php?controller=home" class="btn-ajouter" style="text-decoration: none; padding: 15px 40px; display: inline-block;">Retour à la boutique</a>
        </div>
    <?php else: ?>
        <div style="background: var(--dark-card); border-radius: 15px; padding: 30px; border: 1px solid #222; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid #333;">
                        <th style="padding: 15px; text-align: left; color: var(--text-muted); text-transform: uppercase; font-size: 13px;">Produit</th>
                        <th style="padding: 15px; text-align: center; color: var(--text-muted); text-transform: uppercase; font-size: 13px;">Quantité</th>
                        <th style="padding: 15px; text-align: right; color: var(--text-muted); text-transform: uppercase; font-size: 13px;">Prix Unitaire</th>
                        <th style="padding: 15px; text-align: right; color: var(--text-muted); text-transform: uppercase; font-size: 13px;">Total</th>
                        <th style="padding: 15px; text-align: center; color: var(--text-muted); text-transform: uppercase; font-size: 13px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($panier as $item): ?>
                    <tr style="border-bottom: 1px solid #222;">
                        <td style="padding: 20px 15px;">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <?php if(!empty($item['img'])): ?>
                                    <img src="<?= BASE_URL ?>uploads/<?php echo htmlspecialchars($item['img']); ?>" width="60" style="border-radius: 8px; border: 1px solid #333;">
                                <?php endif; ?>
                                <span style="font-weight: 600; color: var(--text-light);"><?php echo htmlspecialchars($item['produitNom']); ?></span>
                            </div>
                        </td>
                        <td style="padding: 20px 15px; text-align: center;">
                            <div style="display: flex; align-items: center; justify-content: center; gap: 12px;">
                                <a href="<?= BASE_URL ?>index.php?controller=cart&action=decrease&id=<?php echo $item['id']; ?>" 
                                   style="text-decoration: none; color: white; background: #333; width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: bold; transition: background 0.2s;">
                                    -
                                </a>
                                <span style="font-weight: bold; min-width: 20px;"><?php echo $item['qty']; ?></span>
                                <a href="<?= BASE_URL ?>index.php?controller=cart&action=increase&id=<?php echo $item['id']; ?>" 
                                   style="text-decoration: none; color: black; background: var(--primary-yellow); width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: bold; transition: opacity 0.2s;">
                                    +
                                </a>
                            </div>
                        </td>
                        <td style="padding: 20px 15px; text-align: right; color: var(--text-light);">
                            <?php echo number_format($item['prix'], 0, ',', ' '); ?> Fcfa
                        </td>
                        <td style="padding: 20px 15px; text-align: right; color: var(--primary-yellow); font-weight: 700;">
                            <?php echo number_format($item['prix'] * $item['qty'], 0, ',', ' '); ?> Fcfa
                        </td>
                        <td style="padding: 20px 15px; text-align: center;">
                            <a href="<?= BASE_URL ?>index.php?controller=cart&action=remove&id=<?php echo $item['id']; ?>" style="color: #e74c3c; font-size: 18px; transition: opacity 0.2s;">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="padding: 30px 15px; text-align: right; font-weight: 800; font-size: 1.4em; color: var(--text-light); text-transform: uppercase;">Total</td>
                        <td style="padding: 30px 15px; text-align: right; font-weight: 800; font-size: 1.4em; color: var(--primary-yellow);">
                            <?php echo number_format($total, 0, ',', ' '); ?> Fcfa
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div style="margin-top: 40px; text-align: right; display: flex; justify-content: space-between; align-items: center; gap: 20px; flex-wrap: wrap;">
            <a href="<?= BASE_URL ?>index.php?controller=home" style="color: var(--text-muted); text-decoration: none; font-weight: 600; flex: none;">
                <i class="fas fa-arrow-left"></i> Continuer vos achats
            </a>
            <a href="<?= BASE_URL ?>index.php?controller=cart&action=checkout" class="btn-ajouter" style="padding: 15px 30px; font-size: 18px; text-decoration: none; font-weight: 800; flex: none;">
                Passer la commande <i class="fas fa-chevron-right" style="margin-left: 10px;"></i>
            </a>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
