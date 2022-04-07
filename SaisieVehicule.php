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
<center>

<?php

$voiture = liredonnee('voiture');
if($voiture==""){$voiture=0;}
$moto = liredonnee('moto');
if($moto==""){$moto=0;}
$nom =liredonnee('nom');
$puissance = liredonnee('puissance');
if (($voiture == 1 or $moto == 2) and !empty($nom) and !empty($puissance)) {
    if($voiture+$moto==3){
        echo "<h3> Les deux case ne doivent pas être cochées en même temps</h3>";
    }
    $puissancepos=verifierpositif($puissance);
    if($puissancepos==TRUE){
        $type=$voiture+$moto;
        Ajoutervehicule($link,$_SESSION['session_id'],$type,$nom,$puissance);
        echo "<h3> Le véhicule à bien été ajouté</h3>";

    }
}

?>

<h3>Ajouter un véhicule : </h3>

<form action="SaisieVehicule.php" method="post">
<table class="voirfiche" width="10%">
    <td width="10%" align="center">
        voiture :
        <input type="checkbox" name="voiture" value="1"/>
    </td>
    <td width="10%" align="center">
        moto :
        <input type="checkbox" name="moto" value="2"/>
    </td>
</table>
<br><br>
<input placeholder="Nom" name="nom"/><br><br>
<input type="number" placeholder="Puissance" name="puissance"/><br><br>
<button name="valider">Valider</button>
</body>
<div class="Finbody"></div>
<?php include ("Include/Pied.php") ; ?>
</html>