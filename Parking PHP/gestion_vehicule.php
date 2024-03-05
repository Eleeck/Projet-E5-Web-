<?php
    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "wxcvbn?";
    $dbname = "Parking";

    // Création de la connexion
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }

    if (isset($_GET['id_user']) ) {
        $_SESSION['id_user'] = $_GET['id_user'];
     
    } else {
        // Gérer l'absence de nom ou de prénom dans $_GET
        echo "Données manquantes";
    }
    
    $Id= $_SESSION['id_user'];

?>

<!DOCTYPE html>
<html>
    <meta charset="UTF-8" />
    <title>Utilisateur</title>
    <link rel="stylesheet" href="interface.css">
</html>
<body>
    <div class="header" style="background-color: #f2f2f2;">
        <h2 style="font-size: 150%;"> Parking Secure +</h2>
        <h4 style="font-size: medium; font-style: oblique;">Garder une trace de vos véhicules</h4>
    </div>
    <button class ="deco" onclick="window.location.href='deconnection.php'">Déconnexion</button>

    <table>
        <?php
            // Requête SQL pour sélectionner les données sur les véhicules de l'utilisateur
            $sql = "SELECT Place, Modele, Annee FROM Vehicule WHERE Proprietaire = '$Id' ";
            $result = $conn->query($sql);

            // Vérifier s'il y a des données
            if ($result->num_rows > 0) {
                // Afficher les données dans un tableau
                echo "<table><tr><th>ID Place</th><th>Modèle</th><th>Année</th></tr>";
                while($row = $result->fetch_assoc()) {
                    echo "<tr><td>".$row["Place"]."</td><td>".$row["Modele"]."</td><td>".$row["Annee"]."</td></tr>";
                }
                echo "</table>";
            } else {
                echo "Aucun résultat trouvé.";
            }
            $conn->close();
        ?>
    </table>
<div class="formulaire">
            <!-- Formulaire de suppression de véhicule -->
            <div class="form-container2">
                <h2 style="font-size: 150%;">Suppression de Voiture</h2>
                <form id="deleteCarForm" method="post" action="suppression_V.php">
                    <label for="selectCar">Sélectionner le véhicule :</label>
                    <!-- Liste déroulante avec les véhicules à supprimer -->
                    <select id="selectCar" name="carId">
                    <?php
        // Votre code PHP pour récupérer les véhicules à supprimer
        // Assurez-vous que la variable $nom est définie, par exemple à partir de $_SESSION     
        // Assurez-vous également que la connexion à la base de données est établie avant d'exécuter ces requêtes
        // Assurez-vous que la variable $connexion est disponible
        // et que la connexion à la base de données est établie avant cette partie du code
        if (isset($nom)) {
            $query_userId = "SELECT Id_utilisateur FROM Utilisateur WHERE Nom = ?";
            $stmt_userId = mysqli_prepare($connexion, $query_userId);
            mysqli_stmt_bind_param($stmt_userId, "s", $nom);
            mysqli_stmt_execute($stmt_userId);
            mysqli_stmt_bind_result($stmt_userId, $userId);
            mysqli_stmt_fetch($stmt_userId);
            mysqli_stmt_close($stmt_userId);
            if ($userId) {
                $query_cars = "SELECT Id_vehicule, Modele FROM vehicule WHERE Proprietaire = ?";
                $stmt_cars = mysqli_prepare($connexion, $query_cars);
                mysqli_stmt_bind_param($stmt_cars, "i", $userId) ;
                mysqli_stmt_execute($stmt_cars);
                mysqli_stmt_bind_result($stmt_cars, $carId, $carModel);
                // Afficher les options dans la liste déroulante
                while (mysqli_stmt_fetch($stmt_cars)) {
                    echo "<option value='$carId'>$carModel</option>";
                    }
                mysqli_stmt_close($stmt_cars);
            } else {
                echo "Erreur: Utilisateur non trouvé.";
            }
        } else {
            echo "Erreur: Nom d'utilisateur non défini.";
        }
    ?>
                    </select>
                    <br>
                    <input type="submit" value="Supprimer" onclick="showConfirmation()">
                </form>
                <div id="deleteConfirmation" style="display: none;">
                    Êtes-vous sûr de vouloir supprimer ce véhicule ?
                    <button class="button3" onclick="deleteCar()">Oui</button>
                    <button class="button3" onclick="cancelDelete()">Non</button>
                </div>
            </div>
        </div>
</body>