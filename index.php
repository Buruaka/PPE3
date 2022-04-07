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

    $accueil=0;
    include ("Include/Entete.php") ;
    include ("Include/RequeteList.php");

    $Login = lireDonnee("userLogin");
    $MDP = lireDonnee("userMdp");
    $captcha = lireDonnee("captcha");
    
    if (!empty($Login) and !empty($MDP) and $captcha==$_SESSION["code"]) {
        $res=connection($link,$Login, $MDP);
        
        if ($res!=false){
            $Nom=$res["nom"];
            $Prenom=$res["prenom"];
            $Comptable=$res["Comptable"];
            $iduser=$res["iduser"];
            $_SESSION["session_id"]=$iduser;
                $_SESSION["session_nom"]=$Nom;
            $_SESSION["session_prenom"]=$Prenom;
            $_SESSION["session_comptable"]=$Comptable;      
            header('Location: Accueil.php');
        }
        else{
            echo "<h2> Une erreur est survenue durant la connection<br> Veuillez verifier si votre login, mot de passe et le captcha sont bon</h2>";
        }
    }
    ?>
</head>
<body>
    <center>
        <form action="index.php" method="post">
        <h2> Login : </h2>
        <input id="userLogin" name="userLogin"></input><br>
        <h2> Mot de passe : </h2>
        <input type="password" id="userMdp" name="userMdp"></input><br>
        <div class="ButtonBody">
		<img class="captcha" src="Include/image.php" onclick="this.src='Include/image.php?' + Math.random();" alt="captcha" style="cursor:pointer;"><br>
        <input for="txtCaptcha" name="captcha" size=4/><BR>
            <button >Se connecter</button>
        </div>
        </form>
    </center>
    <div class="Finbody"></div>
    <?php include ("Include/Pied.php") ; ?>
</body>
</html>