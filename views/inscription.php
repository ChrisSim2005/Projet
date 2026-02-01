
<?php
    require '../controllers/connexionBDD.php'

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/auth.css">
    <title>Inscription</title>
</head>
<body>
    <section class="signup">
        <div class="left">
            <h1><span>I</span>nscription</h1>
            <br>
            <img src="../image/auth.jpg" alt="">
        </div>
        <div class="right">
            <form action="" method="post">
                <div class="formItem">
                    <label for="nom">Nom</label>
                    <br>
                    <input type="text" placeholder="Votre Nom" name="nom">
                </div>
                <div class="formItem">
                    <label for="prenom">Prénom</label>
                    <br>
                    <input type="text" placeholder="Votre Prénom" name="prenom">
                </div>
                <div class="formItem">
                    <label for="email">Email</label>
                    <br>
                    <input type="email" placeholder="Email" name="email">
                </div>
                <div class="formItem">
                    <label for="password">Mot de passe</label>
                    <br>
                    <input type="password" placeholder="Mot de passe" name="mdp">
                </div>
                <div class="formItem">
                    <label for="password">Comfirmez le mot de passe</label>
                    <br>
                    <input type="password" placeholder="Confirmez le mot de passe" name="cmdp">
                </div>
                

                <input type="submit" class="bouton" value="S'inscrire" name="inscrire">
                <br>
                <h5 class="signOrLogin">Déjà un compte ? <a href="connexion.php" >Se connecter</a></h5>

                <?php
                    if(isset($_POST["inscrire"])){
                        $nom = $_POST["nom"];
                        $prenom = $_POST["prenom"];
                        $email = $_POST["email"];
                        $mdp = $_POST["mdp"];
                        $cmdp = $_POST["cmdp"];

                        if($mdp == $cmdp){
                            try {
                                $req =$pdo->prepare("INSERT INTO utilisateur(nom, prenom, email, mdp) VALUES(?,?,?,?)");
                                $req->execute(array($nom, $prenom, $email, $mdp));
                                echo '<script>window.location.href="index.php"</script>';
                            }catch(PDOException $e){
                                echo "erreur mot de passe différent " .$e->getMessage();
                            }
                        }
                    }
                ?>

            </form>
        </div>
    </section>
</body>
</html>