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
        <h2>Vous avez bien étais déconnecté<br> merci de votre visite et à bientôt </h2>
    </center>
    <div class="Finbody"></div>
    <?php include ("Include/Pied.php");
    session_abort();
    header('location:index.php')
    ?>
    
</body>
</html>