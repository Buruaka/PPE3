<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"  href="CodeCss/Style.css">
    <title>Document</title>

    <?php 
    $accueil=1;
    session_start();
    include ("Include/Entete.php"); 
    include ("Include/RequeteList.php");
    ?>
</head>
<body>

    <?php

    $supprimer=liredonnee('Supprimer');
    $idvehicule=liredonnee('idvehicule');
    if(!empty($supprimer)){
        Supprimervehicule($link,$_SESSION["session_id"],$idvehicule);
    }

    ?>


<center>
    <?php                
    if ($_SESSION["session_comptable"]==1){echo "<image src='./Image/profilcomptable.png' width='100px'/><br>";}
    if ($_SESSION["session_comptable"]==0){echo "<image src='./Image/profil.png' width='100px'/><br>";}
    ?>
    <?php echo "<h3>".$_SESSION["session_prenom"]." ".$_SESSION["session_nom"]."</h3>";?>
    <?php 
                if ($_SESSION["session_comptable"]==1){echo "<h3>Comptable</h3>";}
                if ($_SESSION["session_comptable"]==0){echo "<h3>Visiteur</h3>";}
    
     if ($_SESSION["session_comptable"]==0){?>
    <h3>Vos véhicules</h3>

    <?php 
        $vehicule=affichervehicule($link,$_SESSION['session_id']);
        while($lignevehicule=mysqli_fetch_assoc($vehicule)){
            
            echo "Nom : <b>".$lignevehicule["nom"]."</b> Puissance : <b>".$lignevehicule["Puissance"]."</b>";
            ?>
            <form action="profil.php" method="post">
            <input type="hidden" name="idvehicule" value=<?php echo $lignevehicule["idVehicule"]?>></input>
            <button name="Supprimer" value="Supprimer">Supprimer</button>
        </form>
        <?php } ?>

<br><br><br><br><br><br>
    <a href="SaisieVehicule.php">
        <button>Ajouter un véhicule</button>
    </a>
<?php }?>   
</center>
</body>
<div class="Finbody"></div>
<?php include ("Include/Pied.php") ; ?>
</html>