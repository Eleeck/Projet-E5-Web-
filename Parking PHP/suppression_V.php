<?php
     session_start();
     // Étape 1: Connexion à la base de données
     $server = 'localhost';
     $username = 'root';
     $password = 'wxcvbn?';
     $dbname = 'Parking';
 
     $connexion = mysqli_connect($server, $username, $password, $dbname);
 
     // Vérifier la connexion
     if ($connexion->connect_error) {
         die("La connexion a échoué : " . $connexion->connect_error);
     }

    if(isset($_POST['carId'])) {
            $carId = $_POST['carId'];

            // Établir la connexion à la base de données
            $server = 'localhost';
            $username = 'root';
            $password = 'wxcvbn?';
            $dbname = 'Parking';

            $connexion = mysqli_connect($server, $username, $password, $dbname);

            // Vérifier la connexion
            if ($connexion->connect_error) {
                die("La connexion a échoué : " . $connexion->connect_error);
            }

            // Supprimer le véhicule de la base de données
            $deleteQuery = "DELETE FROM Vehicule WHERE Id_vehicule = ?";
            $stmt = mysqli_prepare($connexion, $deleteQuery);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "i", $carId);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                mysqli_close($connexion);

                // Répondre avec un message de succès
                echo "Le véhicule a été supprimé avec succès.";
            } else {
                // En cas d'erreur dans la préparation de la requête
                echo "Erreur lors de la suppression du véhicule.";
            }
        } else {
            // En cas de données manquantes dans la requête
            echo "ID du véhicule non fourni.";
        }
?>