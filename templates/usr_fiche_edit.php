<?php
/**
 * @var User $userSelected
 * @var string $modAct
 */
?>

<div class="jumbotron jumbotron-fluid">
    <div class="container">
        <h1>Fiche utilisateur</h1>
        <!-- Saisie des infos pour un utilisateur existant -->
    </div>
</div>


<div class="container">
<form method="post" action="update_usr.php">
    <input type="hidden" name="usr_id" value="<?php echo $userSelected->getId(); ?>" />

    <legend>Informations d'identification</legend>
    <div class="form-group">
        <label for="Nom">Nom</label>
        <input class="form-control" id="Nom" type="text" name="usr_name" value="<?php echo $userSelected->getName(); ?>" <?php echo $modAct!=='edit' ? ' disabled' : '' ?> />
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="Profil">Profil</label>
            <input class="form-control" id="Profil" type="text" name="usr_login" value="<?php echo $userSelected->getLogin() ?>" <?php echo $modAct!=='edit' ? ' disabled' : '' ?> />
        </div>
        <div class="form-group col-md-6">
            <label for="Pass">Mot de Passe</label>
            <input type="text" class="form-control" id="Pass" name="usr_pwd" value="<?php echo $userSelected->getPassword(); ?>" <?php echo $modAct!=='edit' ? ' disabled' : '' ?>/>
        </div>
    </div>


    <legend>Informations annexes</legend>
    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="Date">Date d'inscription :</label>
            <input type="date" class="form-control" id="Date" name="usr_create" value="<?php echo $userSelected->getCreationDate(); ?>" <?php echo $modAct!=='edit' ? ' disabled' : '' ?> />

        </div>
        <div class="form-group col-md-4">

            <label for="Profil">Type de profil</label>
            <div class="form-check">
                <!-- Choix bouton radio en checked -->

                <?php
                foreach(User::getRightsValues() as $rightValue){
                        $mustCheckRadio = ($rightValue == $userSelected->getRights());
                        echo '<input class="form-check-input" type="radio" name="usr_right" value=\'' . $rightValue . '\'';
                        echo ($mustCheckRadio? 'checked' : '');
                        echo $modAct!=='edit' ? ' disabled' : '';
                        echo ' > ' . $rightValue . '<br>';
                    }
                ?>
            </div>
        </div>
        <div class="form-group col-md-4">
            <!-- Choix bouton radio en checked -->
            <label for="Enable">Profil actif</label>
            <div class="form-check">
            <?php
            $mustCheckRadio = ($userSelected->getEnable() == 1);

            echo '<input class="form-check-input" type="radio" name="usr_enable" value=1 ';
            echo ($mustCheckRadio ? 'checked' : '');
            echo $modAct!=='edit' ? ' disabled' : '';
            echo '> Oui<br>';

            echo '<input class="form-check-input" type="radio" name="usr_enable" value=0 ';
            echo (!$mustCheckRadio ? 'checked' : '');
            echo $modAct!=='edit' ? ' disabled' : '';
            echo '> Non <br>';
            ?>
        </div>
    <div class="form-group">
        <input class="form-group" type="submit" value="Valider">
    </div>
</form>
</div>
