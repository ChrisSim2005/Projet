<?php
session_start();

// Vérifier si l'utilisateur est connecté ET est admin
if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: connexion.php');
    exit();
}

require '../controllers/connexionBDD.php';



// AJOUTER UN PRODUIT AVEC UPLOAD IMAGE
if(isset($_POST['ajouter'])) {
    $produitNom = $_POST['produitNom'];
    $prix = $_POST['prix'];
    $descrip = $_POST['descrip'];
    
    try {
        // Gestion de l'upload d'image
        $img = '';
        if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $dossier = '../uploads/';
            if(!is_dir($dossier)) {
                mkdir($dossier, 0777, true);
            }
            
            // Générer un nom unique pour l'image
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $nomFichier = uniqid() . '_' . time() . '.' . $extension;
            $cheminComplet = $dossier . $nomFichier;
            
            // Vérifier le type d'image
            $typesAutorises = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if(in_array(strtolower($extension), $typesAutorises)) {
                if(move_uploaded_file($_FILES['image']['tmp_name'], $cheminComplet)) {
                    $img = $nomFichier;
                }
            }
        }
        
        $req = $pdo->prepare("INSERT INTO produits (produitNom, descrip, img, prix) VALUES (?, ?, ?, ?)");
        $req->execute([$produitNom, $descrip, $img, $prix]);
        $message_success = "Produit ajouté avec succès!";
        
        // Rediriger pour éviter le rechargement du formulaire
        header("Location: admin.php?success=1");
        exit();
        
    } catch(PDOException $e) {
        $message_erreur = "Erreur: " . $e->getMessage();
    }
}

// SUPPRIMER UN PRODUIT
if(isset($_GET['supprimer'])) {
    $id = $_GET['supprimer'];
    try {
        // Récupérer le nom de l'image pour la supprimer du dossier
        $req_img = $pdo->prepare("SELECT img FROM produits WHERE id = ?");
        $req_img->execute([$id]);
        $produit = $req_img->fetch();
        
        if($produit && !empty($produit['img'])) {
            $chemin_image = '../uploads/' . $produit['img'];
            if(file_exists($chemin_image)) {
                unlink($chemin_image);
            }
        }
        
        $req = $pdo->prepare("DELETE FROM produits WHERE id = ?");
        $req->execute([$id]);
        
        header("Location: admin.php?deleted=1");
        exit();
        
    } catch(PDOException $e) {
        $message_erreur = "Erreur: " . $e->getMessage();
    }
}

// RÉCUPÉRER TOUS LES PRODUITS
try {
    $req = $pdo->query("SELECT * FROM produits ORDER BY id DESC");
    $produits = $req->fetchAll();
} catch(PDOException $e) {
    $message_erreur = "Erreur: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Produits</title>
    <link rel="stylesheet" href="../css/admin.css">
    <style>
        .messages {
            margin: 20px auto;
            max-width: 800px;
        }
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <h1 class="title">Administration des produits</h1>

    <!-- Messages -->
    <div class="messages">
        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success">Produit ajouté avec succès!</div>
        <?php endif; ?>
        
        <?php if(isset($_GET['deleted'])): ?>
            <div class="alert alert-success">Produit supprimé avec succès!</div>
        <?php endif; ?>
        
        <?php if(isset($message_erreur)): ?>
            <div class="alert alert-error"><?php echo $message_erreur; ?></div>
        <?php endif; ?>
    </div>

    <!-- Formulaire d'ajout -->
    <div class="card add-product">
        <h2>Ajouter un produit</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <input type="text" placeholder="Nom du produit" name="produitNom" required>
            </div>
            
            <div class="form-group">
                <input type="number" placeholder="Prix (FCFA)" name="prix" step="0.01" required>
            </div>
            
            <div class="form-group">
                <textarea placeholder="Description" name="descrip" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label for="image">Image du produit:</label>
                <input type="file" id="image" name="image" accept="image/*">
                <small>Formats acceptés: JPG, PNG, GIF, WebP (max 2MB)</small>
            </div>
            
            <button type="submit" name="ajouter" class="btn">Ajouter le produit</button>
        </form>
    </div>

    <!-- Liste des produits -->
    <h2 style="margin: 30px 0 20px 0; display: flex; align-items: center; justify-content: center;" >Liste des produits</h2>
    
    <?php if(isset($produits) && !empty($produits)): ?>
        <?php foreach($produits as $produit): ?>
        <div class="card product-item">
            <!-- Image du produit -->
            <div class="product-image-container">
                <?php if(!empty($produit['img']) && file_exists('../uploads/' . $produit['img'])): ?>
                    <img src="../uploads/<?php echo htmlspecialchars($produit['img']); ?>" 
                         alt="<?php echo htmlspecialchars($produit['produitNom']); ?>"
                         class="product-image">
                <?php else: ?>
                    <img src="https://via.placeholder.com/80" 
                         alt="Image non disponible"
                         class="product-image">
                <?php endif; ?>
            </div>

            <div class="info">
                <strong><?php echo htmlspecialchars($produit['produitNom']); ?></strong>
                <?php if(!empty($produit['descrip'])): ?>
                    <p class="desc"><?php echo htmlspecialchars($produit['descrip']); ?></p>
                <?php endif; ?>
                <p class="price"><?php echo number_format($produit['prix'], 0, ',', ' '); ?> FCFA</p>
            </div>

            <div class="actions">
                <a href="modifier_produit.php?id=<?php echo $produit['id']; ?>" class="btn-edit">Modifier</a>
                <a href="?supprimer=<?php echo $produit['id']; ?>" 
                   class="btn-delete" 
                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                    Supprimer
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="card">
            <p style="text-align: center; padding: 20px;">Aucun produit disponible pour le moment.</p>
        </div>
    <?php endif; ?>
    <br>
    <div class="btnR">
        <a href="index.php" class="btn">Retour sur la page produit</a>
    </div>
    
</body>
</html>