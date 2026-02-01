<?php
require '../controllers/connexionBDD.php';
session_start();

// Vérifier si $pdo est bien disponible
if (!isset($pdo)) {
    die("Erreur : connexion à la base de données non établie");
}

// Liste des emails autorisés comme admin
$admin_emails = [
    'admin@example.com',
    'administrateur@ecommerce.com',
    'superadmin@ecommerce.com',
    'admin@ecommerce.com'  // Ajoute ton email admin ici
];

// Vérifier la connexion
if(isset($_POST["Connexion"])){
    $email = htmlspecialchars(trim($_POST["email"]));
    $mdp = $_POST["mdp"];
    
    if(!empty($email) && !empty($mdp)){
        try {
            // 1. Vérifier si l'utilisateur existe
            $req = $pdo->prepare("SELECT * FROM utilisateur WHERE email = :email");
            $req->execute(['email' => $email]);
            
            if($req->rowCount() > 0){
                $user = $req->fetch();
                
                // 2. Vérifier le mot de passe
                if($mdp === $user['mdp']) {
                    
                    // Créer la session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_nom'] = $user['nom'];
                    $_SESSION['user_prenom'] = $user['prenom'];
                    
                    // Vérifier si c'est un admin (basé sur l'email)
                    if(in_array($user['email'], $admin_emails)) {
                        $_SESSION['user_role'] = 'admin';
                    } else {
                        $_SESSION['user_role'] = 'client';
                    }
                    
                    // Redirection vers l'index
                    header('Location: index.php');
                    exit();
                } else {
                    $error = "Email ou mot de passe incorrect";
                }
            } else {
                $error = "Email ou mot de passe incorrect";
            }
            
        } catch(PDOException $e){
            $error = "Erreur : " . $e->getMessage();
        }
    } else {
        $error = "Veuillez remplir tous les champs";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/auth.css">
    <title>Connexion</title>
</head>
<body>
    <section class="signup">
        <div class="left">
            <h1><span>C</span>onnexion</h1>
            <br>
            <img src="../image/auth.jpg" alt="Image d'authentification">
        </div> 
        <div class="right">
            <form action="" method="post">
                <div class="formItem">
                    <label for="email">Email</label>
                    <br>
                    <input type="email" placeholder="Email" name="email" required>
                </div>
                
                <div class="formItem">
                    <label for="password">Mot de passe</label>
                    <br>
                    <input type="password" placeholder="Mot de passe" name="mdp" required>
                </div>
                
                <!-- Affichage des erreurs -->
                <?php if(isset($error)): ?>
                    <div class="error" style="color: red; margin: 10px 0;">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <input type="submit" class="bouton" value="Connexion" name="Connexion">
                <br>
                <h5 class="signOrLogin">Pas encore de compte ? <a href="inscription.php">Inscrivez-vous</a></h5>
                <h5 class="signOrLogin">
                    <small>Pour accéder à l'admin: admin@example.com / motdepasse</small>
                </h5>
            </form>
        </div>
    </section>
</body>
</html>