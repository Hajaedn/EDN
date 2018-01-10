<?php

require_once 'ma_lib.php';
include 'header.php';

?>

<h1>Identification</h1>
<!-- Première ligne avec du code PHP -->
Identifiez vous :
<form method="post" action="validate.php">
    <p>
        <input type="text" name="my_id"/><br>
        <input type="password" name="my_pass"/>
        <?php
        $err = isset($_GET['err']) ? $_GET['err'] : 0;
        if ($err == 1) {
            echo('Saisie obligatoire !');
        } else if ($err == 2) {
            echo('Mot de passe invalide !');
        } else if ($err == 3) {
            echo('Profil corrompu !');
        } else if ($err == 4) {
            echo('Profil désactivé !');
        } else if ($err != 0) {
            throw new Exception('Valeur invalide pour err');
        }

//        switch ($err) {
//            case 0:
//                echo "En attente de saisie";
//                break;
//            case 1:
//                echo "";
//                break;
//            case 2:
//                echo "";
//                break;
//            default:
//        throw new Exception('Valeur invalide pour err');
//        }
        ?>

        <br><br><input type="submit" value="Valider">
    </p>
</form>
<?php
include 'footer.php';
?>