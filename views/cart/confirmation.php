<?php require __DIR__ . '/../layout/header.php'; ?>

<main class="confirmation-container" style="padding: 60px 20px; text-align: center; min-height: 80vh; display: flex; align-items: center; justify-content: center;">
    <div class="confirmation-card" style="max-width: 800px; width: 100%; background: var(--dark-card); padding: 60px 40px; border-radius: 20px; border: 1px solid #222; box-shadow: 0 20px 50px rgba(0,0,0,0.5);">
        <div class="confirmation-icon" style="font-size: 80px; color: var(--primary-yellow); margin-bottom: 30px;">
            <i class="fas fa-check-circle"></i>
        </div>
        
        <h1 style="color: var(--text-light); font-size: 42px; font-weight: 800; margin-bottom: 20px; text-transform: uppercase;">Commande confirmée !</h1>
        
        <p class="confirmation-message" style="font-size: 18px; color: var(--text-muted); line-height: 1.8; margin-bottom: 40px; max-width: 600px; margin-left: auto; margin-right: auto;">
            Merci pour votre confiance. Nous avons bien reçu votre commande et nous préparons votre colis avec le plus grand soin.
            Un email de confirmation vous a été envoyé.
        </p>
        
        <div class="confirmation-details" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; text-align: left; margin-bottom: 50px;">
            <div style="padding: 25px; background: #252525; border-radius: 15px; border: 1px solid #333;">
                <i class="fas fa-shipping-fast" style="color: var(--primary-yellow); font-size: 24px; margin-bottom: 15px;"></i>
                <h3 style="margin-bottom: 10px; color: var(--text-light); font-size: 16px;">Livraison Express</h3>
                <p style="margin: 0; color: var(--text-muted); font-size: 14px;">Délai estimé : 24 à 48 heures ouvrées.</p>
            </div>
            
            <div style="padding: 25px; background: #252525; border-radius: 15px; border: 1px solid #333;">
                <i class="fas fa-shield-alt" style="color: var(--primary-yellow); font-size: 24px; margin-bottom: 15px;"></i>
                <h3 style="margin-bottom: 10px; color: var(--text-light); font-size: 16px;">Paiement Sécurisé</h3>
                <p style="margin: 0; color: var(--text-muted); font-size: 14px;">Transaction validée et sécurisée par Shopkip.</p>
            </div>
            
            <div style="padding: 25px; background: #252525; border-radius: 15px; border: 1px solid #333;">
                <i class="fas fa-headset" style="color: var(--primary-yellow); font-size: 24px; margin-bottom: 15px;"></i>
                <h3 style="margin-bottom: 10px; color: var(--text-light); font-size: 16px;">Assistance 24/7</h3>
                <p style="margin: 0; color: var(--text-muted); font-size: 14px;">Une question ? support@shopkip.com</p>
            </div>
        </div>
        
        <div class="confirmation-actions" style="display: flex; flex-direction: column; align-items: center; gap: 15px;">
            <a href="<?= BASE_URL ?>index.php?controller=home" class="btn-ajouter" style="padding: 15px 35px; text-decoration: none; font-size: 18px; font-weight: 800; border-radius: 10px; display: inline-flex; align-items: center; gap: 10px; width: fit-content;">
                <i class="fas fa-shopping-bag"></i> Continuer mes achats
            </a>
            <button onclick="window.print()" style="background: transparent; color: var(--text-muted); border: 1px solid #333; padding: 10px 25px; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 10px; transition: all 0.3s; width: fit-content;">
                <i class="fas fa-print"></i> Imprimer le reçu
            </button>
        </div>
    </div>
</main>

<style>
    button:hover { background: #222 !important; }
    @media print {
        header, .confirmation-actions { display: none !important; }
        .confirmation-container { padding: 0 !important; }
        .confirmation-card { border: none !important; box-shadow: none !important; }
    }
</style>

<?php require __DIR__ . '/../layout/footer.php'; ?>
