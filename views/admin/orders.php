<?php require __DIR__ . '/../layout/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>css/admin.css?v=<?= time(); ?>">

<div class="admin-container">
    <h1 class="title">Gestion des Commandes</h1>

    <div style="margin-bottom: 40px; display: flex; gap: 15px; justify-content: center;">
        <a href="<?= BASE_URL ?>index.php?controller=admin&action=index" class="btn-ajouter" style="text-decoration: none; padding: 12px 25px; border-radius: 8px; display: inline-flex; align-items: center; gap: 10px; background: transparent; border: 1px solid #444; color: var(--text-muted);">
            <i class="fas fa-boxes"></i> Ajouter des Produits
        </a>
    </div>

    <!-- Liste des commandes -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <?php if(isset($commandes) && !empty($commandes)): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>N° COMMANDE</th>
                            <th>CLIENT / DESTINATAIRE</th>
                            <th>COORDONNÉES</th>
                            <th style="text-align: center;">MONTANT</th>
                            <th style="text-align: center;">PAIEMENT</th>
                            <th style="text-align: center;">STATUT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($commandes as $cmd): ?>
                        <tr>
                            <td style="font-weight: 700; color: var(--text-light);">#<?php echo $cmd['id']; ?></td>
                            <td>
                                <strong style="color: var(--text-light);"><?php echo htmlspecialchars($cmd['client_nom']); ?></strong><br>
                                <span style="font-size: 13px; color: var(--text-muted);"><i class="fas fa-map-marker-alt" style="margin-right: 5px;"></i> <?php echo htmlspecialchars($cmd['ville']); ?></span>
                            </td>
                            <td style="font-size: 13px;">
                                <div><i class="fas fa-envelope" style="width: 20px; color: var(--text-muted);"></i> <?php echo htmlspecialchars($cmd['email']); ?></div>
                                <div style="margin-top: 5px;"><i class="fas fa-phone" style="width: 20px; color: var(--text-muted);"></i> <?php echo htmlspecialchars($cmd['telephone']); ?></div>
                            </td>
                            <td style="text-align: center; color: var(--primary-yellow); font-weight: 800; font-size: 16px;">
                                <?php echo number_format($cmd['total'], 0, ',', ' '); ?> F
                            </td>
                            <td style="text-align: center;">
                                <span style="background: rgba(255,255,255,0.05); padding: 5px 12px; border-radius: 12px; font-size: 11px; border: 1px solid #333; text-transform: uppercase;">
                                    <?php echo htmlspecialchars($cmd['methode_paiement']); ?>
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <span class="status-badge status-<?php echo ($cmd['statut'] ?? 'en_attente'); ?>">
                                    <i class="fas <?php echo ($cmd['statut'] == 'termine' ? 'fa-check' : 'fa-clock'); ?>" style="margin-right: 5px;"></i>
                                    <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $cmd['statut'] ?? 'en_attente'))); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div style="padding: 60px; text-align: center; color: var(--text-muted);">
                <i class="fas fa-file-invoice" style="font-size: 48px; margin-bottom: 20px; color: #333;"></i>
                <p>Aucune commande enregistrée pour le moment.</p>
            </div>
        <?php endif; ?>
    </div>

    <div style="margin-top: 60px; text-align: center;">
        <a href="<?= BASE_URL ?>index.php?controller=home" style="color: var(--text-muted); text-decoration: none; font-weight: 600;">
            <i class="fas fa-arrow-left"></i> Retour à la boutique
        </a>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
