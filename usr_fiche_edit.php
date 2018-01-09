<?php
require_once 'ma_lib.php';
session_start();

$pdo = new pdo($dsn, $user, $password, $opt);
$user = User::getFromDataBase($pdo, $_SESSION['id']);
if (empty($user)) {header("Location: index.php");}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Utilisateur</title>
</head>
<body>
<h1>Fiche utilisateur</h1>

<!-- Saisie des infos pour un utilisateur existant -->
<!-- Première ligne avec du code PHP -->
<?php

$key=$_GET["nom"];

try {
    $userToEdit = User::getFromDataBase($pdo, $key);

} catch (Exception $e) {
    die($e->getMessage());
}


if (CheckRight($_SESSION['id'], $key)==false) {
    throw new Exception('Pas les droits');
}

//$query = 'SELECT * FROM users WHERE usr_id =:usr_id';
//$prep = $pdo->prepare($query);
//$prep->bindValue(':usr_id', $userToEdit->getId(), PDO::PARAM_STR);
//$prep->execute();
//$result = $prep->fetch();

//$enable = 'Off';
//if ($result['usr_enable']) {
//    $enable = 'Actif';
//};
?>


<form method="post" action="update_usr.php">
    <p>
        <br>
    <fieldset>
        <legend>Informations d'identification</legend>
        <input type="hidden" name="usr_id" value="<?php echo $userToEdit->getId(); ?>" />
        <b>Nom :  </b><input type="text" name="usr_name" value="<?php echo $userToEdit->getName(); ?>"/><br><br>
        <b>Profil : </b><input type="text" name="usr_login" value="<?php echo $userToEdit->getLogin() ?>"/><br><br>
        <b>Mot de passe : </b><input type="text" name="usr_pwd" value="<?php echo $userToEdit->getPassword(); ?>"/><br><br>
    </fieldset><br>
    <fieldset>
        <legend>Informations annexes</legend>
        <b>Date d'inscription : </b><input type="date" name="usr_create" value="<?php echo $userToEdit->getCreationDate(); ?>"/><br><br>
        <b>Type de profil : </b><br>

        <!-- Choix bouton radio en checked -->

        <?php
        foreach(User::getRightsValues() as $rightValue){

            $mustCheckRadio = ($rightValue == $userToEdit->getRights());

            echo '<input type="radio" name="usr_right" value=\'' . $rightValue . '\'';

            echo ($mustCheckRadio? 'checked' : '') . ' > '. $rightValue . '<br>';

        }
        ?>

<!--            --><?php //if($userToEdit->getRights() == User::RIGHTS_ADMIN) {                ?>

<!--                <input type="radio" name="usr_right" value='admin' checked> Administrateur <br>-->
<!--                <input type="radio" name="usr_right" value='user'> Utilisateur<br><br>-->
<!--            --><?php //} elseif ($userToEdit->getRights() == User::RIGHTS_USER) { ?>
<!--                <input type="radio" name="usr_right" value='admin' > Administrateur <br>-->
<!--                <input type="radio" name="usr_right" value='user' checked> Utilisateur<br><br>-->
<!--            --><?php //} ?>

        <!-- Choix bouton radio en checked -->
        <b>Profil actif : </b><br>
        <?php

        $mustCheckRadio = ($userToEdit->getEnable() == 1);
//        var_dump($mustCheckRadio);
//        die;

        echo '<input type="radio" name="usr_enable" value=1 ';
        echo ($mustCheckRadio ? 'checked' : '');
        echo '> Oui<br>';

        echo '<input type="radio" name="usr_enable" value=0 ';
        echo (!$mustCheckRadio ? 'checked' : '');
        echo '> Non <br>';


        ?>

<!--        --><?php //if($userToEdit->getEnable() == 1) { ?>
<!--                <input type="radio" name="usr_enable" value=1 checked> Oui<br>-->
<!--                <input type="radio" name="usr_enable" value=0> Non<br>-->
<!--        --><?php //} elseif ($userToEdit->getEnable() == 0) { ?>
<!--            <input type="radio" name="usr_enable" value=1> Oui<br>-->
<!--            <input type="radio" name="usr_enable" value=0 checked> Non<br>-->
<!--        --><?php //} ?>
    </fieldset>
        <br><br><input type="submit" value="Valider">
    </p>
</form>
</body>