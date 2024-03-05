<?php
    // Connexion à la base de données
    $connexion = mysqli_connect('localhost', 'root', 'wxcvbn?', 'Parking');

    if (!$connexion) {
        die("La connexion a échoué: " . mysqli_connect_error());
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nom = $_POST['nom'];
        $etage = $_POST['etage'];
        $numero = $_POST['numero'];
    
        // Mettre à jour la base de données pour marquer la place comme occupée et ajouter l'occupant
        $updateQuery = "UPDATE Place SET Etat = 'occupée', Occupant = '$nom' WHERE Etage = $etage AND Numero = $numero";
        if (mysqli_query($connexion, $updateQuery)) {
            echo "Mise à jour réussie";
        } else {
            echo "Erreur lors de la mise à jour: " . mysqli_error($connexion);
        }
    }
    

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Parking</title>
    <link rel="stylesheet" href="parking.css">
</head>
<body>
    <div class="header">
        <h2 style="font-size: 150%;">Parking Secure +</h2>
        <h4 style="font-size: medium; font-style: oblique;">Gardez une trace de vos véhicules</h4>
    </div>
    <br>
    <!-- Boutons de connexion et inscription -->
    <a style="float: right;" href="page_inscription.php"><button class="button2">Inscription</button></a>
    <a href="page_connexion.php"><button class="button1">Connexion</button></a>
    
    <br>
    <center>
        <div class="parking-zone">
            <h1>Parking</h1>
            <br>
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
                                    echo " Libre";
                                    
                                } else if ($etat ==='occupée') {
                                    $placeOccup ++;
                                    echo "Prise";
                                } else {
                                    $etat === 'En_travaux';
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
                <form id="occupyForm" method = "post" action="occuper.php">
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
  
<!-- 
    <div class ="footer">
        <h3 style="font-size: medium; font-style: oblique;">Secure + ne vous demandera jamais vos informations personnelles</h3>
    </div> -->

    

    <script>
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
