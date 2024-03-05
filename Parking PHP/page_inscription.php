<?php

    session_start();
    
    function redirectToPage($nom, $prenom, $type, $id_utilisateur) {
        $_SESSION['nom_utilisateur'] = $nom;
        $_SESSION['prenom_utilisateur'] = $prenom;
        $_SESSION['id_utilisateur'] = $id_utilisateur;
    
        // Redirection vers la page appropriée en fonction du type d'utilisateur
        $redirect_page = ($type === 'administrateur') ? 'page_administrateur.php' : 'page_utilisateur.php';
        header("Location: $redirect_page?nom_utilisateur=$nom&prenom_utilisateur=$prenom&id_utilisateur=$id_utilisateur");
        exit();
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupération des données du formulaire
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $mot_de_passe = $_POST['mot_de_passe'];
        $numero = $_POST['numtel'];
        $adresse = $_POST['adress'];
        $type = $_POST['type'];
    
        // Connexion à la base de données
        $server = 'localhost';
        $username = 'root';
        $password = 'wxcvbn?';
        $dbname = 'Parking';
    
        $connexion = mysqli_connect($server, $username, $password, $dbname);
    
        if (!$connexion) {
            die("La connexion à la base de données a échoué : " . mysqli_connect_error());
        }
    
       // Préparation et exécution de la requête d'insertion
        $requete = "INSERT INTO Utilisateur (Mdp, Nom, Prenom, Num_tel, Mail, Adresse, Type) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($connexion, $requete);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssssss", $mot_de_passe, $nom, $prenom, $numero, $email, $adresse, $type);
            mysqli_stmt_execute($stmt);
            
            // Récupérer l'ID utilisateur après l'insertion
            $id_utilisateur = mysqli_insert_id($connexion);

            mysqli_stmt_close($stmt);
            mysqli_close($connexion);

            // Redirection après l'inscription
            redirectToPage($nom, $prenom, $type, $id_utilisateur);
        } else {
            echo "Erreur dans la préparation de la requête";
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="parking.css">
    <meta charset="UTF-8" />
    <script src="scroll.js"></script>
    <title>Inscription</title>
</head>
<body id="BG2">
    <div class="header">
        <h2 style="font-size: 150%;">Parking Secure +</h2>
        <h4 style="font-size: medium; font-style: oblique;">Gardez une trace de vos véhicules</h4>
    </div>
    <br>
    <div>
        <a href="page_connexion.php"><button class="button1">Connexion</button></a>
        <a href="PArking_secure_+index.php"><button class="button_retour">Accueil</button></a>
    </div>
    <center>
        <div class="inscription">
            <form method="post">
                <fieldset>
                    <legend>Précisez vos informations</legend>
                        <br>
                        <label for="nom">Votre nom :</label>
                        <input type="text" name="nom" id="nom" required>
                        <br>
                        <label for="prenom">Votre prénom :</label>
                        <input type="text" name="prenom" id="prenom" required>
                        <br>
                        <label for="email">Adresse mail :</label>
                        <input type="email" name="email" id="email" required>
                        <br>
                        <label for="mot_de_passe">Mot de passe :</label>
                        <input type="password" name="mot_de_passe" id="mot_de_passe" required>
                        <br>
                        <label for="numtel">Numéro téléphone :</label>
                        <input type="text" name="numtel" id="numtel">
                        <br>
                        <label for="adress">Adresse :</label>
                        <input type="text" name="adress" id="adress">
                        <br>
                        <label for="type">Type :</label>
                        <select name="type" id="type">
                            <option value="utilisateur">Utilisateur</option>
                            <option value="admin">Administrateur</option>
                        </select>
                       <br>
                       <br>
                        <input type="submit" value="S'inscrire">
                </fieldset>
            </form>
        </div>
    </center>
   
    <br>

    <div class ="footer">
        <h3 style="font-size: medium; font-style: oblique;">Secure + ne vous demandera jamais vos informations personnelles à des fins lucratives</h3>
    </div>
</body>
</html>
