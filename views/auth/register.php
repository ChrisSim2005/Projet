<?php require __DIR__ . '/../layout/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>css/auth.css?v=<?= time(); ?>">

<section class="signup">
    <div class="left">
        <h1><span>I</span>nscription</h1>
        <p style="color: var(--text-muted); font-size: 18px; margin-top: -15px;">Rejoignez la communauté Shopkip</p>
        <br>
        <img src="<?= BASE_URL ?>image/register_icon.png" alt="Icone d'inscription">
    </div> 
    <div class="right">
        <form action="<?= BASE_URL ?>index.php?controller=auth&action=register" method="post">
             <div class="formRow">
                <div class="formItem">
                    <label for="nom">Nom</label>
                    <input type="text" placeholder="Nom" name="nom" required>
                </div>
                <div class="formItem">
                    <label for="prenom">Prénom</label>
                    <input type="text" placeholder="Prénom" name="prenom" required>
                </div>
             </div>
            
            <div class="formItem">
                <label for="email">Email</label>
                <input type="email" placeholder="votre@email.com" name="email" required>
            </div>
            
             <div class="formItem">
                <label for="telephone">Téléphone</label>
                <input type="text" placeholder="01 02 03 04 05" name="telephone" required>
            </div>
            
            <div class="formItem">
                <label for="password">Mot de passe</label>
                <input type="password" placeholder="••••••••" name="mdp" required>
            </div>

            <div class="formItem">
                <label for="confirm_password">Confirmation du mot de passe</label>
                <input type="password" placeholder="••••••••" name="confirm_mdp" required>
            </div>
            
            <?php if(isset($error) && !empty($error)): ?>
                <div class="error">
                    <?= $error; ?>
                </div>
            <?php endif; ?>
            
            <input type="submit" class="bouton" value="S'inscrire" name="Inscription">
            
            <h5 class="signOrLogin">Déjà un compte ? <a href="<?= BASE_URL ?>index.php?controller=auth&action=login">Connectez-vous</a></h5>
        </form>
    </div>
</section>

<?php require __DIR__ . '/../layout/footer.php'; ?>
