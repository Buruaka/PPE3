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
        <form action=voirfichecomptable.php method='post'>
        <h3>Selectionner un utilisateur:</h3>
        <select name='visiteur' id='visiteur'>
            <?php  while ($lignevisiteur=mysqli_fetch_assoc($visiteurs)){
            echo "<option value='".$lignevisiteur['iduser']."'>".$lignevisiteur['prenom']." ".$lignevisiteur['nom']."</option>";
            }?>
        </select>
        <br>
        <br>
        <button>valider</button>
        </form>
    <?php   } 
    else {
        

        $idvehicule = liredonnee('voiture');

        $ETP=liredonnee('ETP');
        $Km= liredonnee('Km');
        $Nuit= liredonnee('Nuit');
        $Repas= liredonnee('Repas');
        $mois = liredonnee('mois');
        
        $afficherpdf = liredonnee('Afficherpdf');
        $Modifier = liredonnee('Modifier');
        $Supprimer = liredonnee('Supprimer');
        
        
        $libelle = liredonnee('libelle');
        $moishf = liredonnee('moishf');
        $montant= liredonnee('montant');
        
        $afficherpdfhf = liredonnee('Afficherpdfhf');
        $Modifierhf = liredonnee('Modifierhf');
        $Supprimerhf= liredonnee('Supprimerhf');
        $idfiche= liredonnee('idfiche');
        
        if(!empty($afficherpdf)){
            header('Location :Genpdf.php');
        }
        
        
        if(!empty($Modifier)){
        
            $listenewquantite=[$ETP,$Km,$Nuit,$Repas];
            $quantitepos=verifierpositif($listenewquantite);
            if ($quantitepos==TRUE){
                Updatefichefraisforfait($link,$idvisiteur,$mois,$listenewquantite,$idvehicule);
            }
            else{
                echo "<h3>erreur: les données entrées ne sont pas positives</h3>";
            }
        }
        
        if(!empty($Supprimer)){
           Supprimerfichefraisforfait($link,$idvisiteur,$mois);
            ?><h3> Votre fiche de frais a bien été supprimé</h3><?php 
        }
        
        
        if(!empty($Modifierhf)){
        
            $montantpos=verifierpositif($montant);
            if ($montantpos==TRUE){
                Updatefichehorsforfait($link,$idvisiteur,$moishf,$libelle,$montant,$idfiche);
            }
            else{
                echo "<h3>erreur: les données entrées ne sont pas positives</h3>";
            }
        }
        
        if(!empty($Supprimerhf)){
            Supprimerfichehorsfraisforfait($link,$idvisiteur,$idfiche);
             ?><h3> Votre fiche de frais a bien été supprimé</h3><?php 
         }
        ?>
        
        <h3> Les fiches de frais en forfait : </h3>
        
        
        <?php
 $fichesfraisforfait = ObtenirFicheFrais($link,$idvisiteur);

 while( $fichesfraisforfaitliste= mysqli_fetch_assoc($fichesfraisforfait)) { 
     echo $fichesfraisforfaitliste['mois']."<br><br>";
    $fichefrais =Touteficheforfait($link,$idvisiteur,$fichesfraisforfaitliste['mois']);
    
    $listeidfrais=['Etapes','Kilomètres','Nuits','Repas'];
    $listevalue=['ETP','Km','Nuit','Repas'];
    $n=0;
    
    $voitureaff = affichervehicule($link,$idvisiteur);
    ?>
<form action="voirfichecomptable.php" method="post">
<input type="hidden" name="visiteur" value=<?php echo $idvisiteur;?>> 

    Voiture : <select name="voiture" id="voiture">
    <?php 
    
    while( $voitureliste = mysqli_fetch_assoc($voitureaff)){
        if ($voitureliste['idVehicule']==$fichesfraisforfaitliste['idVehicule']){
            echo "<option value='".$voitureliste['idVehicule']."' selected>".$voitureliste['nom']." - ".$voitureliste['Puissance']." CV</option>";
 
        }
        else{
        echo "<option value='".$voitureliste['idVehicule']."'>".$voitureliste['nom']." - ".$voitureliste['Puissance']." CV</option>";
        }
    }
        ?>
    </select>
    <?php
            $valeurpdf=[];
    while($lignefichefrais= mysqli_fetch_assoc($fichefrais)){
        $lignequantite=$lignefichefrais['quantite'];
        if($n==2){echo "<br><br>";}
        echo $listeidfrais[$n].' : ';
        $moispdf=$lignefichefrais['mois'];
        ?>
            <input type="hidden" id="mois" name="mois" value=<?php echo $lignefichefrais['mois'];?>>

        <input type='number' id=<?php echo $listevalue[$n];?> name=<?php echo $listevalue[$n];?> maxlength="4" width="20px" value=<?php echo $lignefichefrais['quantite'] ?>></input>
        <?php $valeurpdf[$n]= $lignefichefrais['quantite'] ?>
    <?php $n=$n+1;
    
    }
    ?>
    <br>
    <br>

    <table class="voirfiche" width="30%">
        
        </td><td align="center">
            <button type="submit" name='Modifier' value='Modifier'>Modifier</button>
        </td><td align="center">
                           <button name='Supprimer' value='Supprimer'>Supprimer</button>
            </form>
            
            <form action="Genpdf.php" method="post">
                <input type='hidden' name='voiture'value=<?php echo $fichesfraisforfaitliste['idVehicule'];?>/>
                <input type='hidden' name='ETP'value=<?php echo $valeurpdf[0];?>>
                <input type='hidden' name='km'value=<?php echo $valeurpdf[1];?>>
                <input type='hidden' name='Nuit'value=<?php echo $valeurpdf[2];?>>
                <input type='hidden' name='Repas'value=<?php echo $valeurpdf[3];?>>
                <input type='hidden' name='mois'value=<?php echo $moispdf;?>>
                <input type="hidden" name="visiteur" value=<?php echo $idvisiteur;?>> 
                <input type="hidden" name="forfait" value="1">
        <td align="center">
            <button type="submit" name='Afficherpdf' value='Afficher en pdf'>Afficher en pdf</button>
    
        </td>
    </table>
    <?php
    echo "</form>";

    echo "<br>";
 }
        ?> 
        
        <h3> Vos fiches de frais hors forfait : </h3>
        <?php
        $fichesfraishorsforfait = ObtenirmoisHorsForfaitFicheFrais($link,$idvisiteur);
        while ($lignefraishorsforfait=mysqli_fetch_assoc($fichesfraishorsforfait)){
            
            echo $lignefraishorsforfait['mois'];
        ?>
        <form action="voirfichecomptable.php" method="post">
            <input type="hidden" name="visiteur" value=<?php echo $idvisiteur;?>> 
        <br>
            <?php echo "Titre :" ?>
            <input  id="libelle" name="libelle" value=<?php echo $lignefraishorsforfait['libelle'] ?>></input>
            <?php echo "Montant :" ?>
            <input type='number' id="montant" name="montant" value=<?php echo $lignefraishorsforfait['montant'] ?>></input>
            <input type="hidden" id="moishf" name="moishf" value=<?php echo $lignefraishorsforfait['mois']?>></input>
            <input type="hidden" id="idfiche" name="idfiche" value=<?php echo $lignefraishorsforfait['idFiche']?>></input>
            <table class="voirfiche" width="30%">
             <td align="center">
                    <button type="submit" name='Modifierhf' value='Modifier'>Modifier</button>
                </td><td align="center">
                    <button type="submit" name='Supprimerhf' value='Supprimer'>Supprimer</button>
                    </form>
                    <form action="Genpdf.php" method="post">
                        </td><td align="center">
                        <input type="hidden" id="mois" name="mois" value=<?php echo $lignefraishorsforfait['mois']?>></input>
                        <input type="hidden" name="libelle" value=<?php echo $lignefraishorsforfait['libelle'] ?>>
                        <input type="hidden" id="montant" name="montant" value=<?php echo $lignefraishorsforfait['montant']?>></input>
                        <input type="hidden" name="forfait" value="0">  
                        <input type="hidden" name="visiteur" value=<?php echo $idvisiteur;?>> 
                    <button type="submit" name='Afficherpdfhf' value='Afficher en pdf'>Afficher en pdf</button>      
                </table>
        </form> 
            <br>
            <br>
<?php
}
?>
<?php
    }
    ?>






<div class="Finbody"></div>
<?php include ("Include/Pied.php"); ?>
</center>
</body>
</html>