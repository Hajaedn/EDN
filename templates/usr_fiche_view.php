<h1>Fiche utilisateur</h1>

<fieldset>
    <legend>Informations d'identification</legend>
    <b>Nom :  </b><a><?php echo $userToSee->getName(); ?></a> <br><br>
    <b>Profil : </b><a><?php echo $userToSee->getLogin() ?></a><br><br>
    <b>Mot de passe : </b><a><?php echo $userToSee->getPassword(); ?></a>
</fieldset><br>
<fieldset>
    <legend>Informations annexes</legend>
    <b>Date d'inscription : </b><a><?php echo strftime($userToSee->getCreationDate()); ?></a><br><br>
    <b>Type de profil : </b><a><?php echo $userToSee->getRights()== User::RIGHTS_ADMIN ? "Administrateur" : "Utilisateur"; ?></a><br><br>
    <b>Profil actif : </b><a><?php echo $userToSee->getEnable() ? "actif" : "off" ?> ?></a>
</fieldset><br>
<a href="../usr_list.php">Retour</a>

<?php
    if ($action=="suppr") {
?>
    <form method="get" action="../delete_usr.php">
        <p><br>
            <input type="hidden" name="id" value="<?= $userToSee->getId() ?>">
            <br><input type="submit" value="Delete">
        </p>

    </form>
<?php
    }
?>

<?php
include 'footer.php';
?>
