<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div id="page">
        <div class="entete"> 
        <table>
            <td>
                <img class="logo" src="./Image/logo.png">
            </td><td>
                <h1>Galaxy-Swiss Bourdin</h1>
            </td><td/>
        </table>    
        <?php 
        if ($accueil==1 ) {
        ?>
            <center>

                        <a href='Accueil.php'>
                            <button class="ButtonEntete">Accueil</button>
                        </a>
                        <?php
                        if ($_SESSION["session_comptable"]==1){echo "<a href='SaisieFicheComptable.php'>";}
                        if ($_SESSION["session_comptable"]==0){echo "<a href='SaisieFiche.php'>";}
                        ?>
                            <button class="ButtonEntete">Saisie de Fiche de Frais</button>
                        </a>
        
                        <?php
                        if ($_SESSION["session_comptable"]==1){echo "<a href='voirfichecomptable.php'>";}
                        if ($_SESSION["session_comptable"]==0){echo "<a href='VoirFiche.php'>";}
                        ?>
                            <button class="ButtonEntete">Voir les Fiches de Frais</button>
                        </a>
                    
                        <a href='Deconnection.php'>
                            <button class="ButtonEntete">Se déconnecter</button>
                        </a>
            <hr/>
            </center>
        <?php   } ?>
    </div>
</body>
</html>