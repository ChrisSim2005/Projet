<?php require __DIR__ . '/../layout/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>css/landing.css?v=<?= time(); ?>">

<div class="landing-page-container">
    <section class="hero-section">
        <div class="hero-content">
            <h1>Votre goût, notre sélection<br><span>Des produits qui vous ressemblent</span></h1>
            <p>Découvrez notre nouvelle collection exclusive. Des vêtements et accessoires conçus pour tout type de personne qui recherche style et confort au quotidien.</p>
            
            <div class="hero-actions">
                <a href="<?= BASE_URL ?>index.php?controller=home" class="btn-primary">Voir les produits</a>
                <a href="<?= BASE_URL ?>index.php?controller=auth&action=login" class="btn-outline">Se connecter</a>
            </div>
        </div>
    </section>

    <section class="features-bar">
        <div class="feature-item">
            <i class="fas fa-star"></i>
            <h3>Nos Produits</h3>
            <p>Une sélection de nos meilleurs articles.</p>
        </div>
        <div class="feature-item">
            <i class="fas fa-box"></i>
            <h3>Réservations</h3>
            <p>Réservez vos articles préférés.</p>
        </div>
        <div class="feature-item">
            <i class="fas fa-comments"></i>
            <h3>Témoignages</h3>
            <p>Ce que nos clients disent de nous.</p>
        </div>
        <div class="feature-item">
            <i class="fas fa-store"></i>
            <h3>Notre boutique</h3>
            <p>Visitez notre boutique physique.</p>
        </div>
    </section>
</div>

<style>
    /* Correction pour que le header ne soit pas gênant sur la landing */
    header {
        position: absolute;
        width: 100%;
        background: transparent !important;
        z-index: 10;
    }
    nav {
        background: transparent !important;
    }
    body {
        margin: 0;
        background-color: #0d0d0d !important;
    }
</style>

<?php require __DIR__ . '/../layout/footer.php'; ?>
