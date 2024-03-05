<?php
    session_start();

    function redirectToPage($type, $nom, $prenom, $id_utilisateur) {
        $_SESSION['nom_utilisateur'] = $nom;
        $_SESSION['prenom_utilisateur'] = $prenom;
        $_SESSION['id_utilisateur'] = $id_utilisateur;
        
        if ($type === 'admin') {
            header("Location: page_administrateur.php?nom_utilisateur=$nom&prenom_utilisateur=$prenom&id_utilisateur = $id_utilisateur");
        } else {
            header("Location: page_utilisateur.php?nom_utilisateur=$nom&prenom_utilisateur=$prenom&id_utilisateur=$id_utilisateur");
        }
        exit(); 
    }

    function connectDB() {
        $server = 'localhost';
        $username = 'root';
        $password = 'wxcvbn?';
        $dbname = 'Parking';
        return mysqli_connect($server, $username, $password, $dbname);
    }

    function authenticateUser($email, $mot_de_passe) {
        $connexion = connectDB();

        if (!$connexion) {
            die("La connexion à la base de données a échoué : " . mysqli_connect_error());
        }

        $requete = "SELECT id_utilisateur, Nom, Prenom, Type FROM Utilisateur WHERE Mail = '$email' AND Mdp = '$mot_de_passe'";
        $result = mysqli_query($connexion, $requete);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            mysqli_free_result($result);
            mysqli_close($connexion);
    
            if ($row) {
                redirectToPage($row['Type'], $row['Nom'], $row['Prenom'], $row['id_utilisateur']); // Passer l'ID utilisateur à la fonction redirectToPage
            } else {
                echo "Identifiants invalides";
            }
        } else {
            echo "Erreur dans la requête : " . mysqli_error($connexion);
        }
    }
    

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['mot_de_passe'])) {
        $email = $_POST['email'];
        $mot_de_passe = $_POST['mot_de_passe'];
        authenticateUser($email, $mot_de_passe);
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Connexion</title>
    <link rel="stylesheet" href="parking.css">
    <script src="scroll.js"></script>
</head>
<body id ="BG1">
<div class="header">
            <h2 style="font-size: 150%;">Parking Secure +</h2>
            <h4 style="font-size: medium; font-style: oblique;">Garder une trace de vos véhicules</h4>
        </div>
    <div>
        <div>
            <a href="page_inscription.php"><button class="button2">Inscription</button></a>
            <a href="PArking_secure_+index.php"><button class="button_retour">Accueil</button></a>
        </div>
        <br>
        <center>
            <div class="connexion">
                <form method="post">
                    <fieldset>
                        <legend>Précisez vos identifiants</legend>
                        <br>
                        <label for="email">Email :</label>
                        <input type="email" name="email" id="email" required>
                        <br><br>
                        <label for="mot_de_passe">Mot de passe :</label>
                        <input type="password" name="mot_de_passe" id="mot_de_passe" required>
                        <br><br>
                        <input type="submit" value="Se Connecter">
                    </fieldset>
                </form>
            </div>
        </center>
        <br>
        <br>
        
    </div>
    <div class ="footer">
        <h3 style="font-size: medium; font-style: oblique;">Secure + ne vous demandera jamais vos informations personnelles à des fins lucratives</h3>
    </div>

</body>
</html>
