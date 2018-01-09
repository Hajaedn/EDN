<?php
require_once 'ma_lib.php';
session_start();

//vérification de la connexion
$pdo = new pdo($dsn, $user, $password, $opt);
$user = User::getFromDataBase($pdo, $_SESSION['id']);
if (empty($user)) {header("Location: index.php");}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Ciao Johnny</title>
</head>
<body>
<h1>Ma page d'aurevoir</h1>
<!-- Première ligne avec du code PHP -->
<p>
    <?php
    if (!empty($_SESSION['login'])) {
        echo "Vous êtes " . $_SESSION['login'] . "<br>" ."Bonjour !";
    }
    else {
        header("Location: index.php");
    }
    ?>
    <!-- <p align="center">Coucou ! </p> -->
    <form method="post" action="disconnect.php">
<p>
    <br><br><input type="submit" value="Déconnecter">
</p>
</form>

<?php
date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, "fr_FR", 'fra');
$mydate = date('d/m/Y');
$myhour = date('H:i:sa');
$mois = array(1 => 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
$jours = array('dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi');
// echo "Nous sommes le : " . $mydate;
// echo " et il est : " . $myhour;
?>
<p><br>
    <?php
    echo "Nous sommes le : " . $jours[date('w')] . ' ' . date('j') . ' ' . $mois[date('n')] . ' ' . date('Y');
    echo " et il est : " . $myhour;
    ?>
</p>
<form method="post" action="cible.php"
<p>
    <br>
    Que pensez-vous de la mort de l'artiste ?<br>
    <textarea name="votre_avis" rows="6" cols="45"></textarea><br>
    <?php

    // On crée notre array $tristesse
    ?>
    <select name="choix_tristesse">
        <?php
        foreach ($tristesse as $k => $v) {
            echo $k . $v;
            echo '<option value="' . $k . '">' . $v . '</option>';
        }
        // $choix = $tristesse['$k'];
        ?>
    </select>
    <!--                     <option value="choix1">Pas bien grave !</option> -->
    <!--                     <option value="choix2">C'est dommage !</option> -->
    <!--                     <option value="choix3">C'est la fin du monde !</option> -->
    <!--                 </select><br><br>  -->
    <br><br><input type="submit" value="Valider">
</p>
</form>
</body>
<!-- ef="bonjour.php?nom=identite"> dis-moi bonjour !</a><br>
</body>
</html>