<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
   <?php 
    session_start();
   include ("Include/RequeteList.php"); 
   
$idvehicule = liredonnee('voiture');
$ETP= liredonnee('ETP');
$Km= liredonnee('km');
$Nuit= liredonnee('Nuit');
$Repas= liredonnee('Repas');
$mois = liredonnee('mois');
$idvisiteur = liredonnee('visiteur');


$mois=liredonnee('mois');
$libelle=liredonnee("libelle");
$montant=liredonnee("montant");
$forfait=liredonnee("forfait");
if($forfait==1){
?>
</head>
<body>
    <style>
        table{width:100%;}
    </style>
    <table>
        <td>
            <image src=Image/logo.png/>
        </td>
        <td>
        <h1> Votre Fiche de Frais en forfait de <?php echo $mois ;?></h1>
    </table>
    <br>
    <?php $voiture =selectvoiture($link,$idvehicule);
    $voiturefetch = mysqli_fetch_assoc($voiture);?>
    <h3>Voiture utilisée : <?php echo $voiturefetch['nom'];?> avec <?php echo $voiturefetch['Puissance'];?> CV</h3>
    <?php
    $puissance=$voiturefetch['Puissance'];

    if ($voiturefetch['type']==1){
        if($puissance<3){ $puissance=3;}
        if($puissance>7){ $puissance=7;}
        $requete= "select facteur,num from coefficient where Puissance='".$puissance."'and type=1";
        $res=mysqli_query($link,$requete);
        $resfetch=mysqli_fetch_assoc($res);
        $coeff= $resfetch['facteur'];
        $nombre= $resfetch['num'];

    }
?>
    <center>
    <table border="1px">
        <tr>
            <td align='center' width='10%'><b>Type</b></td>
            <td align='center' width='10%'><b>Etapes</b></td>
            <td align='center'  width='10%'>Km</td>
            <td align='center'  width='10%'>Repas</td>
            <td align='center'  width='10%'>Nuits</td>
            <td align='center'  width='10%'>Totale</td>
        </tr>
        <tr>
            <td align='center'><b>Nombre</b></td>
            <td align='center'><?php echo $ETP;?></td>
            <td align='center'><?php echo $Km;?></td>
            <td align='center'><?php echo $Nuit;?></td>
            <td align='center'><?php echo $Repas;?></td>
            <td align='center'></td>
        <tr>
            <?php
            $ETPrembour= intval($ETP)*120;
            $kmrembour=(intval($Km)*$coeff)+$nombre;
            $Repasrembour=intval($Repas)*15;
            $Nuitrembour=intval($Nuit)*40;
            $total = $ETPrembour+$kmrembour+$Repasrembour+$Nuitrembour;
            ?>
            <td align='center'><B>Prix frais remboursés</B></td>
            <td align='center' ><?php echo $ETPrembour." €"?></td>
            <td align='center' ><?php echo $kmrembour." €"?></td>
            <td align='center'><?php echo $Repasrembour." €"?></td>
            <td align='center'><?php echo $Nuitrembour." €"?></td>
            <td align='center'><?php echo $total." €"?></td>
        </tr>
    </table>
<?php 
$requete = "select nom,prenom from utilisateur where iduser='".$idvisiteur."'";
$res = mysqli_query($link,$requete);
$visiteuridentifiant=mysqli_fetch_assoc($res);
?>
</center>
    <?php echo "<H3 style='margin-top:500px;text-align:right;margin-right:10px;'>".$visiteuridentifiant["prenom"]." ".$visiteuridentifiant["nom"]."</h3>";?>
</body>
</html>
<?php
}
else{
    ?>
    </head>
<body>
    <style>
        table{width:100%;}
    </style>
    <table>
        <td>
            <image src=Image/logo.png/>
        </td>
        <td>
        <h1> Votre Fiche de Frais hors forfait de <?php echo $mois ;?></h1>
    </table>
    <br>
    <center>
    <table border="1px">
        <tr>
            <td align='center' width='10%'><b>Titre</b></td>
            <td align='center' width='10%'><b><?php echo $libelle?></b></td>
        </tr>
        <tr>
            <td align='center'><b>Montant remboursé</b></td>
            <td align='center'><?php echo $montant." €";?></td>
        </tr>
    </table>
<?php 
$requete = "select nom,prenom from utilisateur where iduser='".$idvisiteur."'";
$res = mysqli_query($link,$requete);
$visiteuridentifiant=mysqli_fetch_assoc($res);
?>
</center>
    <?php echo "<H3 style='margin-top:500px;text-align:right;margin-right:10px;'>".$visiteuridentifiant["prenom"]." ".$visiteuridentifiant["nom"]."</h3>";?>
</body>
</html>
<?php
}
?>