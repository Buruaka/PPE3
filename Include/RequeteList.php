<?php

////////Connection BDD /////
	
include ('connectionBDD.php');

/////// Verification formulaire ///////

function lireDonnee($nomDonnee, $Defaut="") {
    if ( isset($_POST[$nomDonnee])) {
        $val = $_POST[$nomDonnee];
    return $val;
    }
    else {
        return $Defaut;
    }
}

//////Connection 
function connection($link,$login, $mdp){
    $sql="Select * from utilisateur where login='".$login."' and mdp=md5('". $mdp ."')";
    $request=mysqli_query($link,$sql);
    $ligne = false;     
    if ( $request ) {
        $ligne = mysqli_fetch_assoc($request);
        mysqli_free_result($request);
    }
    return $ligne ;
}


///////////////////////////////////
//////Saisie de Fiche Forfait /////
///////////////////////////////////

/////////////////Verification si utilisateur a une fiche de frais sur mois actuel /////////////////

function existeFicheFrais($link, $unMois, $unIdVisiteur) {
    $requete = "select idVisiteur from fichefrais where idVisiteur='" . $unIdVisiteur . 
              "' and mois='" . $unMois . "'";
    $Resultat = mysqli_query($link,$requete);  
    $ligne = false ;
    if ( $Resultat ) {
        $ligne = mysqli_fetch_assoc($Resultat);
        mysqli_free_result($Resultat);
    }        
    return is_array($ligne) ;
}

///////Verification si une fiche du mois actuel existe/////

function ajouterFicheFrais($link, $unMois, $unIdVisiteur,$idvehicule) {
    $dernierMois = obtenirDernierMoisSaisi($link, $unIdVisiteur);
	$laDerniereFiche = obtenirDetailFicheFrais($link, $dernierMois, $unIdVisiteur);
	if ( is_array($laDerniereFiche) && $laDerniereFiche['idEtat']=='CR'){
		modifierEtatFicheFrais($link, $dernierMois, $unIdVisiteur, 'CL');
	}
    // ajout de la fiche de frais � l'�tat Cr��
        $requete = "insert into fichefrais (idVisiteur, mois, nbJustificatifs, montantValide, idEtat, dateModif,idvehicule) values ('" 
        . $unIdVisiteur 
        . "','" . $unMois . "',0,NULL, 'CR', '" . date("Y-m-d") . "','".$idvehicule."')";
        mysqli_query($link,$requete);

        // ajout des �l�ments forfaitis�s
        $requete = "select id from fraisforfait";
        $res = mysqli_query($link,$requete);
        if ( $res ) {
        $ligne = mysqli_fetch_assoc($res);
        while ( is_array($ligne) ) {
        $idFraisForfait = $ligne["id"];
        // insertion d'une ligne frais forfait dans la base
        $requete = "insert into lignefraisforfait (idVisiteur, mois, idFraisForfait, quantite)
                values ('" . $unIdVisiteur . "','" . $unMois . "','" . $idFraisForfait . "',0)";
        mysqli_query($link,$requete);
        // passage au frais forfait suivant
        $ligne = mysqli_fetch_assoc ($res);
        }
        mysqli_free_result($res);       
    }        
}

/////////////////Obtention du dernier mois de saisi/////////////////

function obtenirDernierMoisSaisi($link, $unIdVisiteur) {
	$requete = "select max(mois) as dernierMois from fichefrais where idVisiteur='" .
            $unIdVisiteur . "'";
	$resultat = mysqli_query($link,$requete);
    $dernierMois = false ;
    if ( $resultat ) {
        $ligne = mysqli_fetch_assoc($resultat);
        $dernierMois = $ligne["dernierMois"];
        mysqli_free_result($resultat);
    }        
	return $dernierMois;
}

/////////////////Obtention des detail dela derniere fiche de frais/////////////////

function obtenirDetailFicheFrais($link, $unMois, $unIdVisiteur) {
    $ligne = false;
    $requete="select IFNULL(nbJustificatifs,0) as nbJustificatifs, Etat.id as idEtat, libelle as libelleEtat, dateModif, montantValide 
    from fichefrais inner join etat on idEtat = Etat.id 
    where idVisiteur='" . $unIdVisiteur . "' and mois='" . $unMois . "'";
    $resultat = mysqli_query($link,$requete);  
    if ( $resultat ) {
        $ligne = mysqli_fetch_assoc($resultat);
    }        
    mysqli_free_result($resultat);
    return $ligne ;
}

/////////////////Modification de la derniere fiche de frais/////////////////

function modifierEtatFicheFrais($link, $unMois, $unIdVisiteur, $unEtat) {
    $requete = "update fichefrais set idEtat = '" . $unEtat . 
               "', dateModif = now() where idVisiteur ='" .
               $unIdVisiteur . "' and mois = '". $unMois . "'";
    mysqli_query($link,$requete);
}
    
///////////////Verifier si les données sont positives///////////////
function verifierpositif($donnee){
    $resultat="";
    if(is_array($donnee)){
        foreach ( $donnee as $num ){
            if ($num >= 0 ){
                $resultat=TRUE;
            }
            else {
                $resultat=FALSE;
                break;
            }
        }
    }
    else{
        if ($donnee > 0 ){
            $resultat=TRUE;
        }   
        else {$resultat=FALSE;}
    }
    return $resultat;
}

///////////////Modifier ligne fiche de frais ///////////////

function modifierEltsForfait($link, $unMois, $unIdVisiteur, $donnee) {
    $n=0;
    $idFraisForfait= ["ETP","KM","NUI","REP"];
    foreach ($donnee as $quantite) {
        
        $requete = "update lignefraisforfait set quantite = quantite +" . $quantite 
                    . " where idVisiteur = '" . $unIdVisiteur . "' and mois = '"
                    . $unMois . "' and idFraisForfait='" . $idFraisForfait[$n]. "'";
      mysqli_query($link,$requete);
      $n=$n+1;

    }
}
///////////////////////////////////
///////////////////////////////////

/////////////////Saisie fiche Hors forfait//////////////////

function ajouterLigneHF($link, $unMois, $unIdVisiteur, $uneDateHF, $unLibelleHF, $unMontantHF) {
    $requete = "insert into lignefraishorsforfait(idVisiteur, mois, date, libelle, montant) 
                values ('" . $unIdVisiteur . "','" . $unMois . "','" . $uneDateHF . "','" . $unLibelleHF . "'," . $unMontantHF .")";
    mysqli_query($link,$requete);
}


/////////////////Affichage derniere fiche/////////////////////

function Afficherfiche($link,$idvisiteur){
    $requete = "select idFraisForfait,quantite
    from lignefraisforfait
    inner join fichefrais  on fichefrais.mois = lignefraisforfait.mois
	where fichefrais.idEtat='CR' and fichefrais.idVisiteur ='".$idvisiteur."'";
}
function ObtenirFicheFrais($link,$unIdVisiteur) {

    $requete= "select *
                from  fichefrais 
                where idvisiteur ='". $unIdVisiteur . "' order by mois desc ";
    $mois=mysqli_query($link,$requete);
    return $mois;
}

function Touteficheforfait($link,$idvisiteur,$mois){

    $requete = "select idFraisForfait,quantite,lignefraisforfait.mois
                from lignefraisforfait
                inner join fraisforfait on fraisforfait.id = lignefraisforfait.idFraisForfait
                where idVisiteur='" . $idvisiteur . "' and mois='" . $mois . "'";
    $fichefrais= mysqli_query($link,$requete);
    return $fichefrais;
}
//////////Suppression de Fiche de frais forfait//////////////
function Supprimerfichefraisforfait($link,$idvisiteur,$mois){

    $requete1="Delete from `lignefraisforfait`
                where mois='".$mois."'and idvisiteur='".$idvisiteur."'";
    $requete2="Delete from `fichefrais`
                where mois='".$mois."'and idVisiteur='".$idvisiteur."'";
    
    mysqli_query($link,$requete1);
    mysqli_query($link,$requete2);
}   
/////////Update de Fiche frais forfait //////////////////////
function Updatefichefraisforfait($link,$idvisiteur,$mois,$liste,$idvehicule){
    $n=0;
    $listeidfrais=['ETP','KM','NUI','REP'];
    while($n!=4){
        $requete="Update lignefraisforfait set quantite='".$liste[$n]."'
                where idFraisForfait='".$listeidfrais[$n]."' and idvisiteur='".$idvisiteur."' and mois='".$mois."'";
        mysqli_query($link,$requete);
        $n=$n+1;
    }

    $requete2="Update fichefrais set idVehicule = '".$idvehicule."' where idVisiteur='".$idvisiteur."'and mois=$mois";
    mysqli_query($link,$requete2);
}
//////////////////////////////////////////////////////////
/////////Voir fiche frais hors forfait////////////////////
//////////////////////////////////////////////////////////

function ObtenirmoisHorsForfaitFicheFrais($link,$idvisiteur){
    $requete= "select *
                from  lignefraishorsforfait
                where idvisiteur ='". $idvisiteur . "' order by mois desc ";
    $res=mysqli_query($link,$requete);
    return $res;
}

/////////Modifier fiche hors forfait///////////////

function Updatefichehorsforfait($link,$idvisiteur,$mois,$libelle,$montant,$idfiche){
    $requete="update lignefraishorsforfait set libelle='".$libelle."',montant='".$montant."'where idVisiteur='".$idvisiteur."' and mois='".$mois."' and idFiche='".$idfiche."'";
    $res=mysqli_query($link,$requete);
}

/////////Modifier fiche hors forfait///////////////

function Supprimerfichehorsfraisforfait($link,$idvisiteur,$idfiche){
    $requete="delete from lignefraishorsforfait where idFiche='".$idfiche."' and idVisiteur='".$idvisiteur."'";
    $res=mysqli_query($link,$requete);
}

//////////////////////////////////////////////////////////
//////////////////Véhicules///////////////////////////////
//////////////////////////////////////////////////////////

function affichervehicule($link,$idvisiteur) {
    $requete="select *
                from vehicule where iduser='".$idvisiteur."'";
    $res= mysqli_query($link,$requete);
    return $res;
}

/////////////////Ajouter Véhicule////////////////////////

function Ajoutervehicule($link,$idvisiteur,$type,$nom,$puissance) {
    $requete="insert into vehicule (iduser,type,nom,puissance) 
    values ('".$idvisiteur."','".$type."','".$nom."','".$puissance."')";
    $res=mysqli_query($link,$requete);
}

////////////////Supprimer Véhicule///////////////////////
function Supprimervehicule($link,$idvisiteur,$idvehicule){
    $requete = "delete from vehicule where idVehicule='".$idvehicule."' and iduser='".$idvisiteur."'";
    $res=mysqli_query($link,$requete);
}

function selectvoiture($link,$idvehicule){
    $requete ="select * from vehicule where idVehicule='".$idvehicule."'";
    $res = mysqli_query($link,$requete);
    return $res;
}

////////////////////////////////////////////////////////////////////////////
////////////////////Saisie Comptable////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////

function listevisiteur($link){
    $requete = "select nom,prenom,iduser from utilisateur where Comptable=0";
    $res = mysqli_query($link,$requete);
    return $res;
}
?>
