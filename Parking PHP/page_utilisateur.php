<?php
session_start();

$server = 'localhost';
$username = 'root';
$password = 'wxcvbn?';
$dbname = 'Parking';

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

$id_user = "SELECT id_utilisateur FROM Utilisateur WHERE Nom = '$nom' and Prenom = '$prenom'"; 
//orange
// Vérifier si les données nécessaires sont présentes dans $_POST
if (isset($_POST['carModel']) && isset($_POST['carYear']) && isset($_POST['name'])) {
    $carModel = $_POST['carModel'];
    $carYear = $_POST['carYear'];
    $name = $_POST['name'];

    // Utilisation de requête préparée pour sécuriser contre les injections SQL
    $request ="SELECT Id_utilisateur from Utilisateur where Nom = ?";
    $stmt_select = mysqli_prepare($connexion, $request);
    mysqli_stmt_bind_param($stmt_select, "s", $name);
    mysqli_stmt_execute($stmt_select);
    mysqli_stmt_bind_result($stmt_select, $userId);
    mysqli_stmt_fetch($stmt_select);

    // Fermer la requête préparée SELECT
    mysqli_stmt_close($stmt_select);

    // Vérifier si l'utilisateur existe avant d'insérer le véhicule
    if ($userId !== null) {
        $query = "INSERT INTO vehicule (Modele, Annee, Proprietaire) VALUES (?, ?, ?)";
        $stmt_insert = mysqli_prepare($connexion, $query);

        if ($stmt_insert) {
            // Utilisation de bind_param pour éviter les injections SQL
            mysqli_stmt_bind_param($stmt_insert, "sis", $carModel, $carYear, $userId);
            mysqli_stmt_execute($stmt_insert);

            // Gestion de la réussite de l'insertion
            if (mysqli_stmt_affected_rows($stmt_insert) >= 0) {
                echo "Véhicule ajouté avec succès !";
            } else {
                echo "Erreur lors de l'ajout du véhicule.";
            }

            mysqli_stmt_close($stmt_insert);
        } else {
            echo "Erreur dans la préparation de la requête.";
        }
    } else {
        echo "L'utilisateur n'existe pas.";
    }
} else {
    // Gérer l'absence de modèle de voiture ou d'année de voiture ou de nom dans $_POST
    echo "Données manquantes";
}

    
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Utilisateur</title>
    <link rel="stylesheet" href="interface.css">
    <script src="scroll.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="header" style="background-color: #f2f2f2;">
        <h2 style="font-size: 150%;"> Parking Secure +</h2>
        <h4 style="font-size: medium; font-style: oblique;">Garder une trace de vos véhicules</h4>
    </div>
    <button class ="deco" onclick="window.location.href='deconnection.php'">Déconnexion</button>

    <div>
        <center>
            <h2 style="font-size: 150%; color: aliceblue; text-decoration:underline;">Bienvenue <?php echo "$prenom $nom"; ?></h2>
            
        </center>
    </div>
    <center>
        <div class="parking-zone">
            <table>
                <thead>
                    <tr>
                        <th>Étages \ Places</th>
                        <?php for ($place = 1; $place <= 20; $place++) { ?>
                            <th>Place <?= $place ?></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                       
                        // Initialiser le tableau de données pour les étages et les places
                        $placeData = [];

                        // Initialise le compteur
                        $placeLibre = 0;
                        $placeOccup = 0;

                        for ($etage = 1; $etage <= 3; $etage++) {
                            $placeData[$etage] = array_fill(1, 20, ''); // Initialiser toutes les places à vide
                        }

                        // Récupérer l'état de chaque place depuis la base de données
                        $requete = "SELECT Numero, Etage, Etat FROM Place";
                        // $compte = "SELECT Numero, Etage FROM Place WHERE Etat ='libre'";
                        $resultat = mysqli_query($connexion, $requete);
                        
                        if (!$resultat) {
                            die("Erreur dans la requête: " . mysqli_error($connexion));
                        }

                        // Remplir le tableau de données avec l'état des places
                        while ($row = mysqli_fetch_assoc($resultat)) {
                            $etat = $row['Etat'];
                            $placeData[$row['Etage']][$row['Numero']] = $etat;
                        }
                        // Afficher les données dans le tableau HTML
                        for ($etage = 1; $etage <= 3; $etage++) {
                            echo "<tr>";
                            echo "<td>Étage $etage</td>";
                            for ($place = 1; $place <= 20; $place++) {
                                $etat = $placeData[$etage][$place];
                                $classe_css = ($etat === 'libre') ? 'libre' : 'occupée';
                                echo "<td class='$classe_css'>";
                                if ($etat === 'libre') {
                                    $placeLibre++ ;
                                    echo "Libre";
                                    
                                } else if ($etat ==='occupée') {
                                    $placeOccup ++;
                                    echo "Prise" ;
                                } else {
                                    $etat = 'En_travaux';
                                    echo $etat;
                                }
                                echo "</td>";
                            }
                            echo "</tr>";
                            // Affichage du compteur de places libres
                            
                        }
                        echo "<p>Nombre de places libres : $placeLibre</p>";
                        echo "<p>Nombre de places occupées : $placeOccup</p>";
                        mysqli_close($connexion);
                    ?>
                </tbody>
            </table>
        </div>
    </center>
    <br>

    <button class ="button3" onclick="afficherFormulaire()">Utiliser une place</button>

    <!-- Modal pour utiliser une place -->
    <div id="myModal" class="modal">
        <span class="close">&times;</span>
        <center>
            <div class="modal-content">
                <h2>Sélection de la place</h2>
                <form id="occupyForm" method = "post" action="occuper_utilisateur.php">
                    <label for="nom">Nom:</label>
                    <input type="text" id="nom" name="nom" required><br><br>
                    
                    <label for="etage">Étage:</label>
                    <input type="number" id="etage" name="etage" required><br><br>
                    
                    <label for="numero">Numéro de place:</label>
                    <input type="number" id="numero" name="numero" required><br><br>
                    
                    <input type="submit" value="Utiliser place">
                </form>
            </div>
        </center>
    </div>
    <br>
    <center>
        <div class ="formulaire">
            <!-- Formulaire d'ajout de véhicule -->
            <div class="form-container">
                <h2 style="font-size: 150%;">Ajout de Voiture</h2>
                <form id="carForm" method="post">
                    <label for="carModel">Modèle :</label>
                    <input type="text" id="carModel" name="carModel" required>
                    <br>
                    <label for="carYear">Année :</label>
                    <input type="text" id="carYear" name="carYear" required>
                    <br>
                    <label for="name">Propriétaire :</label>
                    <input type="text" id="name" name="name" required>
                    <br>
                    <input type="submit" value="Ajouter">
                    <!-- Ici ajouter le message "ajouté" après soumission -->
                </form>
            </div>
        </div>
        <br>

        <?php
            // Construction du lien avec l'ID du véhicule en tant que paramètre
            $url = "gestion_vehicule.php?id_user=" . urlencode($id_user);
        ?>

        <a href="<?php echo $url; ?>"><button class="button3">Supprimer Véhicule</button></a>



    </center>

    
    <script>
       // Fonction pour afficher le message de confirmation
        function showConfirmation() {
            document.getElementById("deleteConfirmation").style.display = "block";
        }

        // Fonction pour annuler la suppression
        function cancelDelete() {
            document.getElementById("deleteConfirmation").style.display = "none";
        }

        // Attend que le document soit complètement chargé
        document.addEventListener("DOMContentLoaded", function () {
            // Sélectionne le formulaire
            var form = document.getElementById("deleteCarForm");

            // Ajoute un gestionnaire d'événements pour le submit
            form.addEventListener("submit", function (event) {
                // Empêche l'action par défaut du formulaire (soumission)
                event.preventDefault();

                // Afficher le message de confirmation
                showConfirmation();
            });
        });

        // JavaScript pour afficher le modal et envoyer les données du formulaire
        function afficherFormulaire() {
            var modal = document.getElementById('myModal');
            modal.style.display = 'block';
        }

        // Événement de clic sur le bouton de fermeture du modal
        var closeBtn = document.querySelector('.close');
        closeBtn.addEventListener('click', function() {
            var modal = document.getElementById('myModal');
            modal.style.display = 'none';
        });


        // Fonction pour fermer le modal
        function closeModal() {
            var modal = document.getElementById('myModal');
            modal.style.display = 'none';
        }
    </script>
</body>
</html>

