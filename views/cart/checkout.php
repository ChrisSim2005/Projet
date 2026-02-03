<?php require __DIR__ . '/../layout/header.php'; ?>

<main class="commande-container" style="padding: 60px 20px; min-height: 80vh;">
    <div class="commande-content" style="display: flex; flex-wrap: wrap; gap: 40px; max-width: 1200px; margin: 0 auto;">
        <!-- Formulaire de commande -->
        <div class="formulaire-commande" style="flex: 2; min-width: 300px; background: var(--dark-card); padding: 40px; border-radius: 20px; border: 1px solid #222;">
            <h2 style="font-size: 28px; font-weight: 800; margin-bottom: 30px; text-transform: uppercase; color: var(--text-light);">Informations de livraison</h2>
            
            <?php if(isset($erreurs) && !empty($erreurs)): ?>
                <div class="error" style="margin-bottom: 30px;">
                    <ul style="list-style: none;">
                        <?php foreach($erreurs as $erreur): ?>
                            <li><i class="fas fa-exclamation-circle"></i> <?php echo $erreur; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <?php if(isset($erreur_bdd)): ?>
                <div class="error" style="margin-bottom: 30px;">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo $erreur_bdd; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" id="form-commande" action="<?= BASE_URL ?>index.php?controller=cart&action=checkout">
                <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 40px;">
                    <div class="form-group" style="display: flex; flex-direction: column; gap: 10px;">
                        <label style="font-size: 13px; font-weight: 600; color: var(--text-muted); text-transform: uppercase;"><i class="fas fa-user"></i> Nom complet *</label>
                        <input type="text" name="nom" required 
                               value="<?php echo $_POST['nom'] ?? ''; ?>"
                               placeholder="Votre nom et prénom"
                               style="background: #252525; border: 1px solid #333; padding: 15px; border-radius: 10px; color: white;">
                    </div>
                    
                    <div class="form-group" style="display: flex; flex-direction: column; gap: 10px;">
                        <label style="font-size: 13px; font-weight: 600; color: var(--text-muted); text-transform: uppercase;"><i class="fas fa-envelope"></i> Email *</label>
                        <input type="email" name="email" required
                               value="<?php echo $_POST['email'] ?? ''; ?>"
                               placeholder="exemple@email.com"
                               style="background: #252525; border: 1px solid #333; padding: 15px; border-radius: 10px; color: white;">
                    </div>
                    
                    <div class="form-group" style="display: flex; flex-direction: column; gap: 10px;">
                        <label style="font-size: 13px; font-weight: 600; color: var(--text-muted); text-transform: uppercase;"><i class="fas fa-phone"></i> Téléphone *</label>
                        <input type="tel" name="telephone" required
                               value="<?php echo $_POST['telephone'] ?? ''; ?>"
                               placeholder="+228 XX XXX XX XX"
                               style="background: #252525; border: 1px solid #333; padding: 15px; border-radius: 10px; color: white;">
                    </div>
                    
                    <div class="form-group" style="display: flex; flex-direction: column; gap: 10px;">
                        <label style="font-size: 13px; font-weight: 600; color: var(--text-muted); text-transform: uppercase;"><i class="fas fa-city"></i> Ville *</label>
                        <input type="text" name="ville" required
                               value="<?php echo $_POST['ville'] ?? ''; ?>"
                               placeholder="Votre ville"
                               style="background: #252525; border: 1px solid #333; padding: 15px; border-radius: 10px; color: white;">
                    </div>

                    <div class="form-group" style="grid-column: span 2; display: flex; flex-direction: column; gap: 10px;">
                        <label style="font-size: 13px; font-weight: 600; color: var(--text-muted); text-transform: uppercase;"><i class="fas fa-home"></i> Adresse de livraison *</label>
                        <textarea name="adresse" rows="3" required
                                  placeholder="Numéro, rue, quartier"
                                  style="background: #252525; border: 1px solid #333; padding: 15px; border-radius: 10px; color: white; resize: none;"><?php echo $_POST['adresse'] ?? ''; ?></textarea>
                    </div>
                </div>
                
                <div class="section-paiement">
                    <h2 style="font-size: 20px; font-weight: 700; margin-bottom: 20px; color: var(--text-light);">Méthode de paiement</h2>
                    
                    <div class="methodes-paiement" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                        <label class="methode-option" style="background: #252525; border: 1px solid #333; padding: 20px; border-radius: 12px; cursor: pointer; display: flex; align-items: center; gap: 15px;">
                            <input type="radio" name="methode_paiement" value="Livraison" required checked>
                            <strong>Paiement à la livraison</strong>
                        </label>
                        
                        <label class="methode-option" style="background: #252525; border: 1px solid #333; padding: 20px; border-radius: 12px; cursor: pointer; display: flex; align-items: center; gap: 15px;">
                            <input type="radio" name="methode_paiement" value="T-money/Flooz" required>
                            <strong>T-money / Flooz</strong>
                        </label>
                    </div>
                </div>
                
                <div class="actions-commande" style="margin-top: 40px; display: flex; justify-content: space-between; align-items: center; gap: 20px; flex-wrap: wrap;">
                    <a href="<?= BASE_URL ?>index.php?controller=cart&action=index" style="color: var(--text-muted); text-decoration: none; font-weight: 600; flex: none;">
                        <i class="fas fa-arrow-left"></i> Retour au panier
                    </a>
                    <button type="submit" name="passer_commande" class="btn-ajouter" style="padding: 15px 30px; border-radius: 10px; font-size: 18px; border: none; font-weight: 800; flex: none; cursor: pointer;">
                        Confirmer la commande <i class="fas fa-check-circle" style="margin-left: 10px;"></i>
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Récapitulatif -->
        <div class="recap-commande" style="flex: 1; min-width: 300px;">
            <div class="recap-card" style="background: var(--dark-card); padding: 30px; border-radius: 20px; border: 1px solid #222;">
                <h2 style="font-size: 20px; font-weight: 800; margin-bottom: 20px; text-transform: uppercase;">Récapitulatif</h2>
                
                <div class="recap-items" style="margin-bottom: 30px;">
                    <?php foreach($panier as $item): ?>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid #222; padding-bottom: 15px;">
                        <div style="display: flex; gap: 15px; align-items: center;">
                            <?php if(!empty($item['img'])): ?>
                                <img src="<?= BASE_URL ?>uploads/<?php echo htmlspecialchars($item['img']); ?>" width="50" style="border-radius: 8px;">
                            <?php endif; ?>
                            <div>
                                <h4 style="margin: 0; font-size: 14px;"><?php echo htmlspecialchars($item['produitNom']); ?></h4>
                                <span style="font-size: 12px; color: var(--text-muted);">Qté: <?php echo $item['qty']; ?></span>
                            </div>
                        </div>
                        <span style="font-weight: 700; color: var(--primary-yellow);"><?php echo number_format($item['prix'] * $item['qty'], 0, ',', ' '); ?> F</span>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="recap-total">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; color: var(--text-muted);">
                        <span>Sous-total</span>
                        <span><?php echo number_format($total, 0, ',', ' '); ?> Fcfa</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 20px; color: var(--text-muted);">
                        <span>Livraison</span>
                        <span>2 000 Fcfa</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding-top: 20px; border-top: 2px solid #333; font-weight: 800; font-size: 22px; color: var(--primary-yellow);">
                        <span>TOTAL</span>
                        <span><?php echo number_format($total + 2000, 0, ',', ' '); ?> F</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .error li { margin-bottom: 5px; }
    input::placeholder, textarea::placeholder { color: #555; }
</style>

<?php require __DIR__ . '/../layout/footer.php'; ?>
