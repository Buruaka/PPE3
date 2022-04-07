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
    $idvisiteur=liredonnee('visiteur');
    if (empty($idvisiteur)){

        $visiteurs=listevisiteur($link);?>
        <form action=SaisieFicheComptable.php method='post'>
        <h3>Selectionner un utilisateur:</h3>
        <select name='visiteur' id='visiteur'>
            <?php  while ($lignevisiteur=mysqli_fetch_assoc($visiteurs)){
            echo "<option value='".$lignevisiteur['iduser']."'>".$lignevisiteur['prenom']." ".$lignevisiteur['nom']."</option>";
            }?>
        </select>
        <br>
        <br>
        <button>valider</button>
    <?php   } 
    else {

        $idvoiture = lireDonnee("voiture");
        $ffKM = lireDonnee("ffKM");
        $ffETP = liredonnee('ffETP');
        $ffNH = lireDonnee("ffNH");
        $ffRR = lireDonnee("ffRR");
        $mois = lireDonnee("ffmois");
        
        $donnee = [$ffKM,$ffETP,$ffNH,$ffRR];
        $donneepos = verifierpositif($donnee);
        
        if(!empty($ffETP) and !empty($ffKM) and !empty($ffNH) and !empty($ffRR)){
        
            $existeFicheFrais = existeFicheFrais($link,$mois,$idvisiteur);
            if($donneepos==TRUE){
                if ( !$existeFicheFrais ) {
                    echo "La fiche à bien été ajoutée";
                    ajouterFicheFrais($link, $mois, $idvisiteur,$idvoiture);
                }
                modifierEltsForfait($link, $mois, $idvisiteur,$donnee   );
            }
            else{
                    echo "<h3>erreur: les données entrées ne sont pas positives</h3>";
            }
            
        }
        elseif((!empty($ffETP) or !empty($ffKM) or !empty($ffNH) or !empty($ffRR)) and (empty($ffETP) or empty($ffKM) or empty($ffNH) or empty($ffRR))){
            echo "<h3>erreur: Tous les champs de la fiche ne sont pas remplis</h3>";
        }
        
        $fhfLibelle = lireDonnee("fhfLibelle");
        $fhfMontant = lireDonnee("fhfMontant");
        $fhfmois = lireDonnee("fhfmois");
        $fhfdate = lireDonnee("fhfdate");
        
        if (!empty($fhfLibelle) and !empty($fhfMontant) and !empty($fhfmois) and !empty($fhfdate)){
            $montantpos=verifierpositif($fhfMontant);
            if ($montantpos==TRUE){
            ajouterLigneHF($link,$fhfmois,$idvisiteur,$fhfdate,$fhfLibelle,$fhfMontant);
            }
            else {
                echo "erreur: Le montant n'est pas positif";
            }
        }
            if((!empty($fhfLibelle) or !empty($fhfMontant)) and (empty($fhfLibelle) or empty($fhfMontant))){
                echo "<h3>erreur: Tous les champs de la fiche ne sont pas remplis</h3>";
            }
        
        
        ?>
        
        
        
        <h4>Date</h4>
        <?php
        $date = date("j / n / Y");
        echo $date;
        ?>
        
        <div class="ficheforfait">
        <form action="SaisieFicheComptable.php" method="post">
        <input type="hidden" name="visiteur" value=<?php echo $idvisiteur;?>> 

            <h4>Fiche de frais en forfait</h4>
            <?php
            $voitureaff = affichervehicule($link,$idvisiteur);
            ?>
            Voiture : <select name="voiture" id="voiture">
        <?php   
            
            while( $voitureliste = mysqli_fetch_assoc($voitureaff)){
                echo "<option value='".$voitureliste['idVehicule']."'>".$voitureliste['nom']." - ".$voitureliste['Puissance']." CV</option>";
            }
                ?>
            </select>
            <br><BR>
            <input type="number" name="ffKM" placeholder="kilomètres"></input><br><br>
            <input type="number" name="ffETP"placeholder="Etape"></input><br><br>
            <input type="number" name="ffNH"placeholder="Nuits à l'hôtel"></input><br><br>
            <input type="number" name="ffRR"placeholder="Repas au Restaurant"></input><br><br>
            <?php $mois = sprintf("%04d%02d", date("Y"), date("m"));?>
            <input type="hidden" name="ffmois" value=<?php echo $mois;?>>
            <button >Envoyer</button>
        </form>
        </div>
        <div class="ficheforfait">
        <form action="SaisieFiche.php" method="post">
            <h4>Fiche de frais hors forfait</h4>
            <input placeholder="Libelle" name="fhfLibelle"></input><br><br>
            <input type="number" placeholder="Montant"  name="fhfMontant"></input><br><br>
            <input type="hidden" name="fhfmois" value=<?php echo $mois;?>>
            <input type="hidden" name="fhfdate" value=<?php echo date("Y-n-j");?>>
            <button >Envoyer</button>
        </form> 
        </div>
  <?php  } ?>

<div class="Finbody"></div>
<?php include ("Include/Pied.php") ; ?>
</center>
</body>
</html>