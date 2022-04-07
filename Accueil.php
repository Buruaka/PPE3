<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"  href="CodeCss/Style.css">
    <title>Document</title>
    <?php 

    session_start();

    $accueil=1;
    include ("Include/Entete.php") ;
    ?>
</head>
<body>
    <center>
        <?php if ($accueil==1){?>
        <h2> Bienvenue sur le logiciel de gestion des visiteurs </h2>
        <?php
         }
         else {
             echo "<h2> Une erreur est survenue durant la connection<br> Veuillez verifier si votre login, mot de passe et le captcha sont bon</h2>";
         }
         ?>
    </center>
    <div class="Finbody"></div>
    <?php include ("Include/Pied.php");?>
</body>
</html>