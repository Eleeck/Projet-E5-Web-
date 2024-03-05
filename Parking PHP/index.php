<?php
    session_start();
?>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="parking.css">
    <script src="scroll.js"></script>
    <title>Parking Secure +</title>
</head>
<body>
    <div class="header">
        <h2 style="font-size: 150%;"> Parking Secure +</h2>
        <h4 style="font-size: medium; font-style: oblique;">Garder une trace de vos véhicules</h4>
    </div>
    <section id ="fondcool1">
       
        <div class="context">
            <div >
                <a href="page_connexion.php"><button class="button1"> Connexion </button></a>
                <a href="page_inscription.php"><button class="button2"> Inscription </button></a>
            </div>
            <div>
                <center>
                    <h2 style="font-size: medium; font-family: Segoe UI;"> Accéder au parking sans connexion</h2>
                    <a href = "acces_Parking.php"><button class="button3">Parking</button></a>
                </center>
            </div>
        </div>
        <div class="area" >
            <ul class="squares">
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
        </div >
    </section>

    <div class ="footer">
        <h3>Secure + ne vous demandera jamais vos informations personnelles</h3>
    </div>
</body>
</html>