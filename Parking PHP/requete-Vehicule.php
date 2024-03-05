<?php
    $query = "DELETE FROM vehicule WHERE Id_vehicule = ?";
    $stmt = mysqli_prepare($connexion, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $carId);
        mysqli_stmt_execute($stmt);

        // Vérifiez si la suppression a affecté des lignes dans la base de données
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "Suppression réussie !";
        } else {
            echo "Aucun véhicule trouvé avec cet ID.";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Erreur dans la préparation de la requête.";
    }

    mysqli_close($connexion);

?>