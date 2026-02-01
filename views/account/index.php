<?php require __DIR__ . '/../layout/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>css/compte.css?v=<?= time(); ?>">

<div class="account-container">
    <h1 style="text-align: center; margin-bottom: 40px; font-size: 32px; color: var(--text-light); text-transform: uppercase; font-weight: 800;">Mon Compte</h1>

    <div class="account-grid">
        <!-- Informations Profil -->
        <div class="account-card">
            <h2>Informations Personnelles</h2>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>index.php?controller=account&action=index" method="POST">
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Prénom</label>
                    <input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Email (Non modifiable)</label>
                    <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled style="background-color: #252525; color: var(--text-muted); border-color: #333;">
                </div>
                <button type="submit" name="update_profile" class="btn-update">Mettre à jour le profil</button>
            </form>
        </div>

        <!-- Sécurité -->
        <div class="account-card">
            <h2>Sécurité</h2>
            
            <?php if (isset($success_pwd)): ?>
                <div class="alert alert-success"><?= $success_pwd ?></div>
            <?php endif; ?>
            
            <?php if (isset($error_pwd)): ?>
                <div class="alert alert-error"><?= $error_pwd ?></div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>index.php?controller=account&action=changePassword" method="POST">
                <div class="form-group">
                    <label>Ancien mot de passe</label>
                    <input type="password" name="old_mdp" required>
                </div>
                <div class="form-group">
                    <label>Nouveau mot de passe</label>
                    <input type="password" name="new_mdp" required>
                </div>
                <div class="form-group">
                    <label>Confirmer le nouveau mot de passe</label>
                    <input type="password" name="confirm_mdp" required>
                </div>
                <button type="submit" name="update_password" class="btn-update">Changer le mot de passe</button>
            </form>
        </div>
    </div>
    <div style="text-align: center; margin-top: 50px; margin-bottom: 50px;">
        <a href="<?= BASE_URL ?>index.php?controller=home" style="color: var(--text-muted); text-decoration: none; font-weight: 600;">
            <i class="fas fa-arrow-left"></i> Retour à la boutique
        </a>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
