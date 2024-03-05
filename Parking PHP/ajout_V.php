<?php
    session_start();
    // Étape 1: Connexion à la base de données
    $server = 'localhost';
    $username = 'root';
    $password = 'wxcvbn?';
    $dbname = 'Parking';

    $connexion = mysqli_connect($server, $username, $password, $dbname);

    $nom = $_GET['nom_utilisateur'];
    $prenom = $_GET['prenom_utilisateur'];


    // Vérifier la connexion
    if ($connexion->connect_error) {
        die("La connexion a échoué : " . $connexion->connect_error);
    }

   
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Ajouter le véhicule à la base de données
        $carModel = $_POST['carModel'];
        $carYear = $_POST['carYear'];
        $nom = $_GET['nom_utilisateur'];
    
        // Insérer le véhicule en incluant le nom de l'utilisateur dans la colonne Proprietaire
        $insertQuery = "INSERT INTO Vehicule (Proprietaire, Modele, Annee) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($connexion, $insertQuery);
    
        if ($stmt) {
            $proprietaire = $nom; 
    
            mysqli_stmt_bind_param($stmt, "sss", $proprietaire, $carModel, $carYear);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            mysqli_close($connexion);
    
            echo "success"; // Répondre avec un message de succès
        } else {
            echo "Erreur lors de la préparation de la requête d'insertion";
        }
    }
?>