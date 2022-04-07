<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
        <div class="pied"> 
        <?php if ($accueil==1) {?>
       <hr>
       
        <table>
            <td>
                <Table>
                    
                        <td align='center' width="70px">
                        <a href="profil.php">
                        <?php                
                        if ($_SESSION["session_comptable"]==1){echo "<Image src='./Image/profilcomptable.png' width='50px'/><br>";}
                        if ($_SESSION["session_comptable"]==0){echo "<Image src='./Image/profil.png' width='50px'/><br>";}
                        ?>
                        </a>
                        </td><td>
                        <a href="profil.php">
                            <?php echo $_SESSION["session_prenom"]." ".$_SESSION["session_nom"];?>
                        </a>
                        </td>
                    
                </table>
            </td>
            <td>
                <?php 
                if ($_SESSION["session_comptable"]==1){echo "Comptable";}
                if ($_SESSION["session_comptable"]==0){echo "Visiteur";}
                ?>
                </td align="right">
        </table>
        <?php } ?>
    </div>
</body>
</html>