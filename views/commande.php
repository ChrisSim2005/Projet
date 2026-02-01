<?php
session_start();
require '../controllers/connexionBDD.php';

// Vérifier si le panier n'est pas vide
if(empty($_SESSION['panier'])) {
    header('Location: panier.php');
    exit();
}

// Calculer le total
$total = 0;
foreach($_SESSION['panier'] as $item) {
    $total += $item['prix'] * $item['quantite'];
}

// Traitement du formulaire de commande
if(isset($_POST['passer_commande'])) {
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $telephone = htmlspecialchars($_POST['telephone']);
    $adresse = htmlspecialchars($_POST['adresse']);
    $ville = htmlspecialchars($_POST['ville']);
    $methode_paiement = $_POST['methode_paiement'];
    
    // Validation basique
    $erreurs = [];
    
    if(empty($nom)) $erreurs[] = "Le nom est requis";
    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $erreurs[] = "Email invalide";
    if(empty($telephone)) $erreurs[] = "Le téléphone est requis";
    if(empty($adresse)) $erreurs[] = "L'adresse est requise";
    if(empty($methode_paiement)) $erreurs[] = "Veuillez choisir une méthode de paiement";
    
    if(empty($erreurs)) {
        try {
            // 1. Enregistrer le client
            $req_client = $pdo->prepare("INSERT INTO clients (nom, email, telephone, adresse, ville) 
                                        VALUES (?, ?, ?, ?, ?)");
            $req_client->execute([$nom, $email, $telephone, $adresse, $ville]);
            $client_id = $pdo->lastInsertId();
            
            // 2. Enregistrer la commande
            $req_commande = $pdo->prepare("INSERT INTO commandes (client_id, total, methode_paiement, statut) 
                                          VALUES (?, ?, ?, 'en_attente')");
            $req_commande->execute([$client_id, $total, $methode_paiement]);
            $commande_id = $pdo->lastInsertId();
            
            // 3. Enregistrer les détails de la commande
            foreach($_SESSION['panier'] as $item) {
                $req_details = $pdo->prepare("INSERT INTO details_commande (commande_id, produit_id, quantite, prix_unitaire) 
                                             VALUES (?, ?, ?, ?)");
                $req_details->execute([$commande_id, $item['id'], $item['quantite'], $item['prix']]);
            }
            
            // 4. Vider le panier
            unset($_SESSION['panier']);
            
            // 5. Rediriger vers la page de confirmation
            $_SESSION['commande_id'] = $commande_id;
            header('Location: confirmation.php');
            exit();
            
        } catch(PDOException $e) {
            $erreur_bdd = "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/commande.css">
    <title>Passer la commande - E-commerce</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <nav>
            <h1>E-commerce</h1>
            <div class="etape">
                <span class="etape-item active">1. Panier</span>
                <span class="etape-separator">›</span>
                <span class="etape-item active">2. Commande</span>
                <span class="etape-separator">›</span>
                <span class="etape-item">3. Paiement</span>
                <span class="etape-separator">›</span>
                <span class="etape-item">4. Confirmation</span>
            </div>
        </nav>
    </header>

    <main class="commande-container">
        <div class="commande-content">
            <!-- Formulaire de commande -->
            <div class="formulaire-commande">
                <h2>Informations personnelles</h2>
                
                <?php if(isset($erreurs) && !empty($erreurs)): ?>
                    <div class="alert erreur">
                        <i class="fas fa-exclamation-triangle"></i>
                        <ul>
                            <?php foreach($erreurs as $erreur): ?>
                                <li><?php echo $erreur; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <?php if(isset($erreur_bdd)): ?>
                    <div class="alert erreur">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?php echo $erreur_bdd; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" id="form-commande">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nom"><i class="fas fa-user"></i> Nom complet *</label>
                            <input type="text" id="nom" name="nom" required 
                                   value="<?php echo $_POST['nom'] ?? ''; ?>"
                                   placeholder="Votre nom et prénom">
                        </div>
                        
                        <div class="form-group">
                            <label for="email"><i class="fas fa-envelope"></i> Email *</label>
                            <input type="email" id="email" name="email" required
                                   value="<?php echo $_POST['email'] ?? ''; ?>"
                                   placeholder="exemple@email.com">
                        </div>
                        
                        <div class="form-group">
                            <label for="telephone"><i class="fas fa-phone"></i> Téléphone *</label>
                            <input type="tel" id="telephone" name="telephone" required
                                   value="<?php echo $_POST['telephone'] ?? ''; ?>"
                                   placeholder="+228 XX XXX XX XX">
                        </div>
                        
                        <div class="form-group full-width">
                            <label for="adresse"><i class="fas fa-home"></i> Adresse de livraison *</label>
                            <textarea id="adresse" name="adresse" rows="3" required
                                      placeholder="Numéro, rue, quartier"><?php echo $_POST['adresse'] ?? ''; ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="ville"><i class="fas fa-city"></i> Ville *</label>
                            <input type="text" id="ville" name="ville" required
                                   value="<?php echo $_POST['ville'] ?? ''; ?>"
                                   placeholder="Votre ville">
                        </div>
                    </div>
                    
                    <div class="section-paiement">
                        <h2> Méthode de paiement</h2>
                        
                        <div class="methodes-paiement">
                            <div class="methode-option">
                                <input type="radio" id="paiement_livraison" name="methode_paiement" value="livraison" required>
                                <label for="paiement_livraison">
                                    <div class="methode-icon">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                    <div class="methode-info">
                                        <h4>Paiement à la livraison</h4>
                                        <p>Payez en espèces lors de la réception</p>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="methode-option">
                                <input type="radio" id="paiement_carte" name="methode_paiement" value="carte" required>
                                <label for="paiement_carte">
                                    <div class="methode-icon">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                    <div class="methode-info">
                                        <h4>Carte de crédit/débit</h4>
                                        <p>Visa, MasterCard, etc.</p>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="methode-option">
                                <input type="radio" id="paiement_paypal" name="methode_paiement" value="paypal" required>
                                <label for="paiement_paypal">
                                    <div class="methode-icon">
                                        <i class="fab fa-paypal"></i>
                                    </div>
                                    <div class="methode-info">
                                        <h4>PayPal</h4>
                                        <p>Paiement sécurisé via PayPal</p>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="methode-option">
                                <input type="radio" id="paiement_wave" name="methode_paiement" value="wave" required>
                                <label for="paiement_wave">
                                    <div class="methode-icon">
                                        <i class="fas fa-mobile-alt"></i>
                                    </div>
                                    <div class="methode-info">
                                        <h4>T-money</h4>
                                        <p>Paiement mobile</p>
                                    </div>
                                </label>
                            </div>
                            
                        </div>
                        
                        <!-- Formulaire carte de crédit (caché par défaut) -->
                        <div id="form-carte" class="form-carte" style="display: none;">
                            <h3><i class="fas fa-lock"></i> Informations de carte</h3>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="numero_carte">Numéro de carte</label>
                                    <input type="text" id="numero_carte" name="numero_carte" 
                                           placeholder="1234 5678 9012 3456" maxlength="19">
                                    <div class="cartes-icons">
                                        <i class="fab fa-cc-visa"></i>
                                        <i class="fab fa-cc-mastercard"></i>
                                        <i class="fab fa-cc-amex"></i>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="nom_carte">Nom sur la carte</label>
                                    <input type="text" id="nom_carte" name="nom_carte" 
                                           placeholder="Nom comme sur la carte">
                                </div>
                                
                                <div class="form-group">
                                    <label for="expiration">Date d'expiration</label>
                                    <input type="text" id="expiration" name="expiration" 
                                           placeholder="MM/AA">
                                </div>
                                
                                <div class="form-group">
                                    <label for="cvv">CVV</label>
                                    <input type="text" id="cvv" name="cvv" 
                                           placeholder="123" maxlength="4">
                                    <span class="cvv-info" title="3 ou 4 chiffres au dos de la carte">
                                        <i class="fas fa-question-circle"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="actions-commande">
                        <a href="panier.php" class="btn-retour">
                            <i class="fas fa-arrow-left"></i> Retour au panier
                        </a>
                        <button type="submit" name="passer_commande" class="btn-confirmer">
                            <i class="fas fa-check-circle"></i> Confirmer la commande
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Récapitulatif de la commande -->
            <div class="recap-commande">
                <div class="recap-card">
                    <h2>Récapitulatif</h2>
                    
                    <div class="recap-items">
                        <?php foreach($_SESSION['panier'] as $item): ?>
                        <div class="recap-item">
                            <div class="recap-item-img">
                                <?php if(!empty($item['img']) && file_exists('../uploads/' . $item['img'])): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($item['img']); ?>" 
                                         alt="<?php echo htmlspecialchars($item['produitNom']); ?>">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/60" alt="Produit">
                                <?php endif; ?>
                            </div>
                            <div class="recap-item-info">
                                <h4><?php echo htmlspecialchars($item['produitNom']); ?></h4>
                                <p class="recap-quantite">Quantité: <?php echo $item['quantite']; ?></p>
                            </div>
                            <div class="recap-item-prix">
                                <?php echo number_format($item['prix'] * $item['quantite'], 0, ',', ' '); ?> Fcfa
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="recap-total">
                        <div class="recap-ligne">
                            <span>Sous-total</span>
                            <span><?php echo number_format($total, 0, ',', ' '); ?> Fcfa</span>
                        </div>
                        <div class="recap-ligne">
                            <span>Livraison</span>
                            <span>2 000 Fcfa</span>
                        </div>
                        <div class="recap-ligne total">
                            <span><strong>Total à payer</strong></span>
                            <span><strong><?php echo number_format($total + 2000, 0, ',', ' '); ?> Fcfa</strong></span>
                        </div>
                    </div>
                    
                    <div class="recap-info">
                        <div class="info-item">
                            <i class="fas fa-shipping-fast"></i>
                            <div>
                                <h4>Livraison express</h4>
                                <p>Livraison sous 24-48h</p>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-shield-alt"></i>
                            <div>
                                <h4>Paiement sécurisé</h4>
                                <p>Fiable à 100%</p>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-headset"></i>
                            <div>
                                <h4>Support 24/7</h4>
                                <p>Assistance clientèle</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
    // Afficher/masquer le formulaire de carte
    document.querySelectorAll('input[name="methode_paiement"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const formCarte = document.getElementById('form-carte');
            if(this.value === 'carte') {
                formCarte.style.display = 'block';
                // Rendre les champs carte obligatoires
                document.getElementById('numero_carte').required = true;
                document.getElementById('nom_carte').required = true;
                document.getElementById('expiration').required = true;
                document.getElementById('cvv').required = true;
            } else {
                formCarte.style.display = 'none';
                // Rendre les champs carte facultatifs
                document.getElementById('numero_carte').required = false;
                document.getElementById('nom_carte').required = false;
                document.getElementById('expiration').required = false;
                document.getElementById('cvv').required = false;
            }
        });
    });
    
    // Formatage du numéro de carte
    document.getElementById('numero_carte')?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
        e.target.value = value;
    });
    
    // Formatage de la date d'expiration
    document.getElementById('expiration')?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if(value.length >= 2) {
            value = value.substring(0,2) + '/' + value.substring(2,4);
        }
        e.target.value = value;
    });
    
    // Validation du formulaire
    document.getElementById('form-commande').addEventListener('submit', function(e) {
        const methodePaiement = document.querySelector('input[name="methode_paiement"]:checked');
        if(!methodePaiement) {
            e.preventDefault();
            alert('Veuillez sélectionner une méthode de paiement');
            return false;
        }
        
        if(methodePaiement.value === 'carte') {
            // Validation basique de la carte
            const numeroCarte = document.getElementById('numero_carte').value.replace(/\s/g, '');
            const expiration = document.getElementById('expiration').value;
            const cvv = document.getElementById('cvv').value;
            
            if(numeroCarte.length < 16) {
                e.preventDefault();
                alert('Numéro de carte invalide');
                return false;
            }
            
            if(!expiration.match(/^\d{2}\/\d{2}$/)) {
                e.preventDefault();
                alert('Date d\'expiration invalide (format MM/AA)');
                return false;
            }
            
            if(cvv.length < 3) {
                e.preventDefault();
                alert('CVV invalide');
                return false;
            }
        }
    });
    </script>
</body>
</html>