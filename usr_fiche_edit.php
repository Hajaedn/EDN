<?php
require_once 'ma_lib.php';
session_start();

$pdo = new pdo($dsn, $user, $password, $opt);
$user = User::getFromDataBase($pdo, $_SESSION['id']);
if (empty($user)) {header("Location: index.php");}
require_once 'header.php';
?>

<div class="jumbotron jumbotron-fluid">
    <div class="container">
        <h1>Fiche utilisateur</h1>

        <!-- Saisie des infos pour un utilisateur existant -->
        <!-- Première ligne avec du code PHP -->
        <?php

        $key=$_GET["nom"];

        try {
            $userToEdit = User::getFromDataBase($pdo, $key);

        } catch (Exception $e) {
            //utilisateur non connecté
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
    </div>
</div>

<div class="container">

<form method="post" action="update_usr.php">
    <input type="hidden" name="usr_id" value="<?php echo $userToEdit->getId(); ?>" />
    <div class="form-group">
            <legend>Informations d'identification</legend>
            <label for="Nom">Nom</label>
            <input class="form-control" id="Nom" type="text" name="usr_name" value="<?php echo $userToEdit->getName(); ?>"/><br>
            <b>Profil : </b><input class="form-control" id="InputIdentification" type="text" name="usr_login" value="<?php echo $userToEdit->getLogin() ?>"/><br>
            <b>Mot de passe : </b><input type="text" class="form-control" id="InputIdentification" name="usr_pwd" value="<?php echo $userToEdit->getPassword(); ?>"/><br>

    </div>
    <div class="form-group">
        <fieldset>
            <label>Informations annexes</label>
            <b>Date d'inscription : </b><input type="date" class="form-control" name="usr_create" value="<?php echo $userToEdit->getCreationDate(); ?>"/><br><br>
            <b>Type de profil : </b><br>

            <!-- Choix bouton radio en checked -->

            <?php
            foreach(User::getRightsValues() as $rightValue){

                $mustCheckRadio = ($rightValue == $userToEdit->getRights());

                echo '<input type="radio" name="usr_right" value=\'' . $rightValue . '\'';

                echo ($mustCheckRadio? 'checked' : '') . ' > '. $rightValue . '<br>';

            }
            ?>
            <!-- Choix bouton radio en checked -->
            <b>Profil actif : </b><br>
            <?php

            $mustCheckRadio = ($userToEdit->getEnable() == 1);


            echo '<input type="radio" name="usr_enable" value=1 ';
            echo ($mustCheckRadio ? 'checked' : '');
            echo '> Oui<br>';

            echo '<input type="radio" name="usr_enable" value=0 ';
            echo (!$mustCheckRadio ? 'checked' : '');
            echo '> Non <br>';
            ?>
        </fieldset>
    </div>
    <br><input type="submit" value="Valider">

</form>
</div>

</body>