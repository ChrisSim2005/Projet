<?php
session_start();

// Vérifier si l'utilisateur vient bien d'une commande
if(!isset($_SESSION['commande_id'])) {
    header('Location: inscription.php');
    exit();
}

$commande_id = $_SESSION['commande_id'];
unset($_SESSION['commande_id']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/commande.css">
    <title>Confirmation de commande - E-commerce</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <nav>
            <h1>E-commerce</h1>
            <div class="etape">
                <span class="etape-item">1. Panier</span>
                <span class="etape-separator">›</span>
                <span class="etape-item">2. Commande</span>
                <span class="etape-separator">›</span>
                <span class="etape-item">3. Paiement</span>
                <span class="etape-separator">›</span>
                <span class="etape-item active">4. Confirmation</span>
            </div>
        </nav>
    </header>

    <main class="confirmation-container">
        <div class="confirmation-card">
            <div class="confirmation-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <h1>Commande confirmée !</h1>
            
            <p class="confirmation-message">
                Merci pour votre commande ! Nous avons bien reçu votre paiement et nous préparons votre colis.
                Vous recevrez un email de confirmation avec les détails de votre commande sous peu.
            </p>
            
            <div class="confirmation-details">
                <div class="detail-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h3>Email de confirmation</h3>
                        <p>Un email détaillé vous sera envoyé dans quelques minutes</p>
                    </div>
                </div>
                
                <div class="detail-item">
                    <i class="fas fa-shipping-fast"></i>
                    <div>
                        <h3>Livraison</h3>
                        <p>Délai estimé: 24-48 heures</p>
                    </div>
                </div>
                
                <div class="detail-item">
                    <i class="fas fa-headset"></i>
                    <div>
                        <h3>Support client</h3>
                        <p>Contactez-nous à support@ecommerce.com ou au +228 92 22 85 05</p>
                    </div>
                </div>
            </div>
            
            <div class="confirmation-actions">
                <a href="index.php" class="btn-retour-boutique">
                    <i class="fas fa-shopping-bag"></i> Retour à la boutique
                </a>
                <button class="btn-imprimer" onclick="window.print()">
                    <i class="fas fa-print"></i> Imprimer la facture
                </button>
            </div>
        </div>
    </main>
</body>
</html>