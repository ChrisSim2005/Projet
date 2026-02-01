<?php require __DIR__ . '/../layout/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>css/auth.css?v=<?= time(); ?>">

<section class="signup">
    <div class="left">
        <h1><span>S</span>e connecter</h1>
        <p style="color: var(--text-muted); font-size: 18px; margin-top: -15px;">Accédez à votre univers Shopkip</p>
        <br>
        <img src="<?= BASE_URL ?>image/login_icon.png" alt="Icone de connexion">
    </div> 
    <div class="right">
        <form action="<?= BASE_URL ?>index.php?controller=auth&action=login" method="post">
            <div class="formItem">
                <label for="email">Email</label>
                <input type="email" placeholder="votre@email.com" name="email" required>
            </div>
            
            <div class="formItem">
                <label for="password">Mot de passe</label>
                <input type="password" placeholder="••••••••" name="mdp" required>
            </div>
            
            <?php if(isset($error) && !empty($error)): ?>
                <div class="error">
                    <?= $error; ?>
                </div>
            <?php endif; ?>
            
            <input type="submit" class="bouton" value="Connexion" name="Connexion">
            
            <h5 class="signOrLogin">Pas encore de compte ? <a href="<?= BASE_URL ?>index.php?controller=auth&action=register">Créer un compte</a></h5>
            <h5 class="signOrLogin" style="margin-top: 10px; font-size: 12px; opacity: 0.6;">
                Admin: admin@example.com / motdepasse
            </h5>
        </form>
    </div>
</section>

<?php require __DIR__ . '/../layout/footer.php'; ?>
