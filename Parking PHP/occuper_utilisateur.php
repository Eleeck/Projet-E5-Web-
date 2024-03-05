<?php
    // Connexion à la base de données
    $connexion = mysqli_connect('localhost', 'root', 'wxcvbn?', 'Parking');

    if (!$connexion) {
        die("La connexion a échoué: " . mysqli_connect_error());
    }

    // Récupérer les données du formulaire
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nom = $_POST['nom'];
        $etage = $_POST['etage'];
        $numero = $_POST['numero'];

        // Récupérer l'identifiant de l'utilisateur
        $selection = "SELECT id_utilisateur FROM Utilisateur WHERE Nom = '$nom'";
        $resultat = mysqli_query($connexion, $selection);

        if ($resultat) {
            $row = mysqli_fetch_assoc($resultat);
            $id_utilisateur = $row['id_utilisateur'];

            // Mettre à jour l'état de la place et son occupant dans la base de données
            $requete = "UPDATE Place SET Etat = 'occupée', Occupant = $id_utilisateur WHERE Etage = $etage AND Numero = $numero";

            if (mysqli_query($connexion, $requete)) {
                echo "Mise à jour réussie !";
                 // Rediriger la page après la mise à jour réussie
                header("Location: page_utilisateur.php");
                exit(); //arrêter l'exécution du script

            } else {
                echo "Erreur lors de la mise à jour: " . mysqli_error($connexion);
            }
        } else {
            echo "Erreur lors de la récupération de l'identifiant de l'utilisateur: " . mysqli_error($connexion);
        }

    }

    // Fermer la connexion à la base de données
    mysqli_close($connexion);
?>
