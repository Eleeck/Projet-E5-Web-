
<?php
    session_start();
    // Connexion à la base de données
    $server = 'localhost';
    $username = 'root';
    $password = 'wxcvbn?';
    $dbname = 'Parking';
    
    // Vérifier la connexion
    $connexion = mysqli_connect($server, $username, $password, $dbname);
  
    if (!$connexion) {
        die("La connexion à la base de données a échoué : " . mysqli_connect_error());
    }
  
    if (isset($_GET['nom_utilisateur']) && isset($_GET['prenom_utilisateur'])) {
        $_SESSION['nom_utilisateur'] = $_GET['nom_utilisateur'];
        $_SESSION['prenom_utilisateur'] = $_GET['prenom_utilisateur'];
        
    } else {
        // Gérer l'absence de nom ou de prénom dans $_GET
        echo "Données manquantes";
    }

    $nom = $_SESSION['nom_utilisateur'];
    $prenom = $_SESSION['prenom_utilisateur'];
  
        
    // Récupération des utilisateurs
    $sqlU = "SELECT* FROM Utilisateur";
    $result_U = $connexion->query($sqlU);
    $users = ($result_U->num_rows > 0) ? $result_U->fetch_all(MYSQLI_ASSOC) : [];

    // Récupération des véhicules
    $sqlV = "SELECT* FROM vehicule";
    $result_V = $connexion->query($sqlV);
    $vehicles = ($result_V->num_rows > 0) ? $result_V->fetch_all(MYSQLI_ASSOC) : [];

    // Récupération des places
    $sqlP = "SELECT* FROM place WHERE Occupant is not NULL";
    $result_P = $connexion->query($sqlP);
    $places = ($result_P->num_rows > 0) ? $result_P->fetch_all(MYSQLI_ASSOC) : [];


    $connexion->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Administrateur</title>
    <link rel="stylesheet" href="interface.css">
    <script src="scroll.js"></script>
</head>
<body>
    <div class="header" id="admin">
        <h2 style="font-size: 150%;"> Parking Secure +</h2>
        <h4 style="font-size: medium; font-style: oblique;">Garder une trace de vos véhicules</h4>
    </div>
    <button class ="deco" onclick="window.location.href='deconnection.php'">Déconnexion</button>

    <center>
    <div class ="centre">
        <h2>Tableau des Utilisateurs</h2>
        <?php if (!empty($users)): ?>
            <table>
                <!-- En-têtes de colonnes -->
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <!-- ... Les autres colonnes ... -->
                </tr>
                <!-- Affichage des utilisateurs -->
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user["id_utilisateur"] ?></td>
                        <td><?= $user["Nom"] ?></td>
                        <td><?= $user["Prenom"] ?></td>
                        <td><?= $user["Mail"] ?></td>
                        <td><?= $user["Num_tel"] ?></td>

                        <!-- ... Les autres colonnes ... -->
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Aucune donnée trouvée dans la base de données pour les utilisateurs.</p>
        <?php endif; ?>

        <br>

        <!-- Répéter le même processus pour les véhicules -->
        <h2>Tableau des Véhicules</h2>
        <?php if (!empty($vehicles)): ?>
            <table>
                <!-- En-têtes de colonnes -->
                <tr>
                    <th>ID</th>
                    <th>Modèle</th>
                    <th>Propriétaire</th>
                    <th>Date Ajout</th>
                    <!-- ... Les autres colonnes ... -->
                </tr>
                <!-- Affichage des véhicules -->
                <?php foreach ($vehicles as $vehicle): ?>
                    <tr>
                        <td><?= $vehicle["Id_vehicule"] ?></td>
                        <td><?= $vehicle["Modele"] ?></td>
                        <td><?= $vehicle["Proprietaire"] ?></td>
                        <td><?= $vehicle["Date_ajout"] ?></td>
                        <!-- ... Les autres colonnes ... -->
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Aucune donnée trouvée dans la base de données pour les véhicules.</p>
        <?php endif; ?>

        <br>

        <h2>Tableau des Places utilisées</h2>
        <?php if (!empty($places)): ?>
            <table id ="Big">
                <!-- En-têtes de colonnes -->
                <tr>
                    <th>ID</th>
                    <th>Numéro</th>
                    <th>Etage</th>
                    <th>ID Occupant</th>
                    <!-- ... Les autres colonnes ... -->
                </tr>
                <!-- Affichage des véhicules -->
                <?php foreach ($places as $place): ?>
                    <tr>
                        <td><?= $place["Id_place"] ?></td>
                        <td><?= $place["Numero"] ?></td>
                        <td><?= $place["Etage"] ?></td>
                        <td><?= $place["Occupant"] ?></td>
                        <!-- ... Les autres colonnes ... -->
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Aucune donnée trouvée dans la base de données pour les places.</p>
        <?php endif; ?>

    </div>
    </center>

</body>
</html>
