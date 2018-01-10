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

    <legend>Informations d'identification</legend>
    <div class="form-group">
        <label for="Nom">Nom</label>
        <input class="form-control" id="Nom" type="text" name="usr_name" value="<?php echo $userToEdit->getName(); ?>"/>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="Profil">Profil</label>
            <input class="form-control" id="Profil" type="text" name="usr_login" value="<?php echo $userToEdit->getLogin() ?>"/>
        </div>
        <div class="form-group col-md-6">
            <label for="Pass">Mot de Passe</label>
            <input type="text" class="form-control" id="Pass" name="usr_pwd" value="<?php echo $userToEdit->getPassword(); ?>"/>
        </div>
    </div>


    <legend>Informations annexes</legend>
    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="Date">Date d'inscription :</label>
            <input type="date" class="form-control" id="Date" name="usr_create" value="<?php echo $userToEdit->getCreationDate(); ?>"/>

        </div>
        <div class="form-group col-md-4">

            <label for="Profil">Type de profil</label>
            <div class="form-check">
                <!-- Choix bouton radio en checked -->

                <?php
                foreach(User::getRightsValues() as $rightValue){
                        $mustCheckRadio = ($rightValue == $userToEdit->getRights());
                        echo '<input class="form-check-input" type="radio" name="usr_right" value=\'' . $rightValue . '\'';
                        echo ($mustCheckRadio? 'checked' : '') . ' > '. $rightValue . '<br>';
                    }
                ?>
            </div>
        </div>
        <div class="form-group col-md-4">
            <!-- Choix bouton radio en checked -->
            <label for="Enable">Profil actif</label>
            <div class="form-check">
            <?php
            $mustCheckRadio = ($userToEdit->getEnable() == 1);

            echo '<input class="form-check-input" type="radio" name="usr_enable" value=1 ';
            echo ($mustCheckRadio ? 'checked' : '');
            echo '> Oui<br>';

            echo '<input class="form-check-input" type="radio" name="usr_enable" value=0 ';
            echo (!$mustCheckRadio ? 'checked' : '');
            echo '> Non <br>';
            ?>
        </div>
    <div class="form-group">
        <input class="form-group" type="submit" value="Valider">
    </div>
</form>
</div>

</body>