<?php
require_once 'ma_lib.php';
session_start();
include 'header.php';
?>

<h1>Fiche utilisateur</h1>
<!-- Première ligne avec du code PHP -->
<?php
    $key=$_GET["id"];
    $action=$_GET["action"];


if (empty($_SESSION['login'])) {
    header("Location: index.php");
}

$pdo = new pdo($dsn, $user, $password, $opt);

try {
    $userToSee = User::getFromDataBase($pdo, $key);
} catch (Exception $e) {
    //pas connecté ou problème de connexion bdd
    die($e->getMessage());
}

$enable = 'Off';
if ($userToSee->getEnable()) {
    $enable = 'Actif';
};
$type_prf = 'Utilisateur';
if ($userToSee->getRights() == User::RIGHTS_ADMIN) {
    $type_prf = 'Administrateur';
};
?>

<fieldset>
    <legend>Informations d'identification</legend>
    <b>Nom :  </b><a><?php echo $userToSee->getName(); ?></a> <br><br>
    <b>Profil : </b><a><?php echo $userToSee->getLogin() ?></a><br><br>
    <b>Mot de passe : </b><a><?php echo $userToSee->getPassword(); ?></a>
</fieldset><br>
<fieldset>
    <legend>Informations annexes</legend>
    <b>Date d'inscription : </b><a><?php echo strftime($userToSee->getCreationDate()); ?></a><br><br>
    <b>Type de profil : </b><a><?php echo $type_prf; ?></a><br><br>
    <b>Profil actif : </b><a><?php echo $enable; ?></a>
</fieldset><br>
<a href="usr_list.php">Retour</a>

<?php
    if ($action=="suppr") {
?>
    <form method="get" action="delete_usr.php">
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
