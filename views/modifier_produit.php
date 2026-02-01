<?php
// modifier_produit.php
require '../controllers/connexionBDD.php';

// Vérifier si un ID de produit est passé en paramètre
if(!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: admin.php');
    exit();
}

$id = intval($_GET['id']);

// RÉCUPÉRER LES INFORMATIONS DU PRODUIT À MODIFIER
try {
    $req = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
    $req->execute([$id]);
    $produit = $req->fetch();
    
    // Vérifier si le produit existe
    if(!$produit) {
        header('Location: admin.php');
        exit();
    }
} catch(PDOException $e) {
    die("Erreur: " . $e->getMessage());
}

// TRAITEMENT DU FORMULAIRE DE MODIFICATION
if(isset($_POST['modifier'])) {
    $produitNom = $_POST['produitNom'];
    $prix = $_POST['prix'];
    $descrip = $_POST['descrip'];
    
    // Garder l'ancienne image par défaut
    $img = $produit['img'];
    
    // Gestion de l'upload de la nouvelle image
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $dossier = '../uploads/';
        if(!is_dir($dossier)) {
            mkdir($dossier, 0777, true);
        }
        
        // Vérifier et générer un nom de fichier
        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $typesAutorises = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if(in_array(strtolower($extension), $typesAutorises)) {
            // Supprimer l'ancienne image si elle existe
            if(!empty($produit['img']) && file_exists($dossier . $produit['img'])) {
                unlink($dossier . $produit['img']);
            }
            
            // Générer un nouveau nom de fichier
            $nomFichier = uniqid() . '_' . time() . '.' . $extension;
            $cheminComplet = $dossier . $nomFichier;
            
            if(move_uploaded_file($_FILES['image']['tmp_name'], $cheminComplet)) {
                $img = $nomFichier;
            }
        }
    }
    
    // Si on coche "Supprimer l'image actuelle"
    if(isset($_POST['supprimer_image']) && $_POST['supprimer_image'] == '1') {
        if(!empty($produit['img']) && file_exists('../uploads/' . $produit['img'])) {
            unlink('../uploads/' . $produit['img']);
            $img = '';
        }
    }
    
    try {
        // Mettre à jour en base de données
        $req = $pdo->prepare("UPDATE produits SET produitNom = ?, prix = ?, descrip = ?, img = ? WHERE id = ?");
        $req->execute([$produitNom, $prix, $descrip, $img, $id]);
        
        $message_success = "Produit modifié avec succès!";
        
        // Mettre à jour les données affichées
        $produit['produitNom'] = $produitNom;
        $produit['prix'] = $prix;
        $produit['descrip'] = $descrip;
        $produit['img'] = $img;
        
    } catch(PDOException $e) {
        $message_erreur = "Erreur: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le produit</title>
    <link rel="stylesheet" href="../css/admin.css">
    <style>
        .container {
            max-width: 700px;
            margin: 40px auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .message {
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 5px;
            font-weight: bold;
        }
        .success { 
            background: #d4edda; 
            color: #155724; 
            border: 1px solid #c3e6cb;
        }
        .error { 
            background: #f8d7da; 
            color: #721c24; 
            border: 1px solid #f5c6cb;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }
        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }
        .image-preview {
            margin-top: 10px;
            text-align: center;
        }
        .image-preview img {
            max-width: 200px;
            max-height: 200px;
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 5px;
            background: #f8f9fa;
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }
        .checkbox-group input[type="checkbox"] {
            width: auto;
        }
        .btn-container {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background: #545b62;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        .required {
            color: #dc3545;
        }
        .file-info {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        .current-image {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
            border: 1px dashed #dee2e6;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="title">Modifier le produit</h1>
        
        <!-- Messages de succès/erreur -->
        <?php if(isset($message_success)): ?>
            <div class="message success"><?php echo $message_success; ?></div>
        <?php endif; ?>
        
        <?php if(isset($message_erreur)): ?>
            <div class="message error"><?php echo $message_erreur; ?></div>
        <?php endif; ?>
        
        <!-- Formulaire de modification -->
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $produit['id']; ?>">
            
            <div class="form-group">
                <label for="produitNom">Nom du produit <span class="required">*</span></label>
                <input type="text" id="produitNom" name="produitNom" 
                       value="<?php echo htmlspecialchars($produit['produitNom']); ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="prix">Prix (FCFA) <span class="required">*</span></label>
                <input type="number" id="prix" name="prix" step="0.01"
                       value="<?php echo $produit['prix']; ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="descrip">Description</label>
                <textarea id="descrip" name="descrip" rows="4"><?php echo htmlspecialchars($produit['descrip']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="image">Image du produit</label>
                
                <!-- Afficher l'image actuelle -->
                <?php if(!empty($produit['img']) && file_exists('../uploads/' . $produit['img'])): ?>
                    <div class="current-image">
                        <p><strong>Image actuelle:</strong></p>
                        <div class="image-preview">
                            <img src="../uploads/<?php echo $produit['img']; ?>" 
                                 alt="Image actuelle du produit">
                        </div>
                        <p class="file-info">Fichier: <?php echo $produit['img']; ?></p>
                        
                        <div class="checkbox-group">
                            <input type="checkbox" id="supprimer_image" name="supprimer_image" value="1">
                            <label for="supprimer_image">Supprimer cette image</label>
                        </div>
                    </div>
                <?php else: ?>
                    <p>Aucune image actuellement.</p>
                <?php endif; ?>
                
                <!-- Upload nouvelle image -->
                <p style="margin-top: 15px;"><strong>Changer l'image:</strong></p>
                <input type="file" id="image" name="image" accept="image/*">
                <p class="file-info">Formats acceptés: JPG, PNG, GIF, WebP (max 2MB)</p>
                <p class="file-info">Laissez vide pour conserver l'image actuelle.</p>
            </div>
            
            <div class="btn-container">
                <button type="submit" name="modifier" class="btn btn-primary">Enregistrer les modifications</button>
                <a href="admin.php" class="btn btn-secondary">Retour à la liste</a>
                <a href="?id=<?php echo $produit['id']; ?>" class="btn btn-danger">Annuler les modifications</a>
            </div>
        </form>
    </div>
</body>
</html>