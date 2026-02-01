<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"> <!-- Encodage des caractères -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Mobile-friendly -->
    <!-- Charge la feuille de style globale avec un paramètre pour vider le cache -->
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css?v=<?= time(); ?>">
    <!-- Charge les icônes FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Boutique Shopkip</title>
</head>
<body>
    <?php 
    // Détecte si on est sur certaines pages spéciales pour adapter le menu
    $is_admin_page = isset($_GET['controller']) && $_GET['controller'] === 'admin';
    $is_account_page = isset($_GET['controller']) && $_GET['controller'] === 'account';
    $is_landing_page = !isset($_GET['controller']) || $_GET['controller'] === 'landing';
    
    // N'affiche le header classique que si on n'est pas sur une page admin ou compte
    if(!$is_admin_page && !$is_account_page): 
    ?>
    <header>
        <nav>
            <!-- Logo du site -->
            <h1><a href="<?= BASE_URL ?>index.php" style="text-decoration:none;">Shop<span>kip</span></a></h1>
            <div class="admin-actions">
                <?php if(!isset($_SESSION['user_id'])): ?>
                    <!-- Liens pour les visiteurs non connectés -->
                    <a href="<?= BASE_URL ?>index.php?controller=auth&action=register" class="btn-inscrire">
                        <i class="fas fa-user-plus"></i> S'inscrire
                    </a>
                    <a href="<?= BASE_URL ?>index.php?controller=auth&action=login" class="btn-admin" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);">
                        <i class="fas fa-sign-in-alt"></i> Connexion
                    </a>
                <?php else: ?>
                    <!-- Message de Bienvenue personnalisé -->
                    <span class="welcome-msg">
                        Bienvenue, <strong><?= htmlspecialchars($_SESSION['user_prenom'] ?? $_SESSION['user_nom'] ?? 'Utilisateur') ?></strong>
                    </span>

                    <!-- Lien vers le profil utilisateur -->
                    <a href="<?= BASE_URL ?>index.php?controller=account&action=index" class="link-icon" title="Mon Compte">
                        <i class="fas fa-user-circle"></i>
                    </a>
                    
                    <!-- Affichage du lien Admin uniquement pour les administrateurs -->
                    <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <a href="<?= BASE_URL ?>index.php?controller=admin&action=index" class="link-icon" title="Administration">
                            <i class="fas fa-user-shield"></i>
                        </a>
                    <?php endif; ?>

                    <!-- Bouton de déconnexion -->
                    <a href="<?= BASE_URL ?>index.php?controller=auth&action=logout" class="link-icon" title="Déconnexion" style="color: var(--accent-red);">
                        <i class="fas fa-power-off"></i>
                    </a>
                <?php endif; ?>
                
                <!-- Section Panier (cachée pour l'admin) -->
                <?php if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin'): ?>
                    <a href="<?= BASE_URL ?>index.php?controller=cart&action=index" style="text-decoration: none;">
                        <div class="panier" title="Mon Panier">
                            <i class="fas fa-shopping-bag" style="font-size: 20px; color: var(--primary-yellow);"></i>
                            <?php 
                            // Calcule le nombre d'articles dans le panier
                            $cart_count = 0;
                            if(isset($_SESSION['panier'])) {
                                $cart_count = is_array($_SESSION['panier']) ? count($_SESSION['panier']) : 0;
                            }
                            if($cart_count > 0): 
                            ?>
                                <!-- Badge affichant le nombre d'articles -->
                                <span class="badge-panier"><?php echo $cart_count; ?></span>
                            <?php endif; ?>
                         </div>
                    </a>
                <?php endif ?>
            </div>
        </nav>
    </header>
    <?php endif; ?>
