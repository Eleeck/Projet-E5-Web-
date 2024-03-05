<?php
    // Démarrez la session
    session_start();

    // Détruisez toutes les variables de session
    $_SESSION = array();

    // Détruisez la session
    session_destroy();

    // Redirigez l'utilisateur vers la page de connexion ou une autre page
    header("Location: PArking_secure_+index.php");
    exit;
?>
