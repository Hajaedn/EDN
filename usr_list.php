<?php
require_once 'ma_lib.php';
session_start();
if (!CheckId($_SESSION['id'])) {header("Location: index.php");}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Utilisateurs</title>
</head>
<body>
<h1>Liste des utilisateurs</h1>

<a>Vous êtes : </a><?php echo $_SESSION['name'] ?>

<?php
$pdo = new pdo($dsn, $user, $password, $opt);

// Liste des Utilisateurs "actifs"

$query = 'SELECT * FROM users';
$prep = $pdo->prepare($query);
$prep->execute();

$arrAll=$prep->fetchAll();

?>
<p>Créer un utilisateur <a href="usr_new.php">Création</a><br></p>

<!-- Tableau : Liste des utilisateurs -->
<table border=2 cellspacing="2" width="80%" align="center">
    <!-- Ligne titre -->
    <tr>
        <th width="15%"> Utilisateur </th>
        <th width="15%"> Profil </th>
        <th width="15%"> Mot de passe </th>
        <th width="15%"> Droits </th>
        <th width="15%"> Date de création </th>
        <th width="10%"> Actif O/N </th>
        <th width="15%"> Action </th>
    </tr>
<?php
    foreach($arrAll as $ligne) {
        $name = $ligne['usr_name'];
        $id = $ligne['usr_id'];
        $login = $ligne['usr_login'];
        $pwd = $ligne['usr_pwd'];
        $right = $ligne['usr_right'];
        $create = $ligne['usr_create'];
        $enable = 'off';
        if ($ligne['usr_enable']) {
            $enable = 'actif';
        };
    ?>
    <tr>
        <td><?= $name ?></td>
        <td><?= $login ?></td>
        <td><?= $pwd ?></td>
        <td align='center'><?= $right ?></td>
        <td align='center'><?= $create ?></td>
        <td align='center'><?= $enable ?></td>
        <td><a href='usr_fiche_view.php?nom=<?= $id ?>&action=voir'>Voir</a>
            <?php if (($_SESSION['sess_droits'] == 'admin') || ($_SESSION['id']==$id)){ ?>
            <a href="usr_fiche_edit.php?nom=<?= $id ?>">Edit</a>
            <a href='usr_fiche_view.php?nom=<?= $id ?>&action=suppr'>Delete</a>
                <?php } ?>
        </td>
    </tr>

<?php
    }
?>
</table>

<form method="post" action="disconnect.php">
    <p>
        <br><br><input type="submit" value="Déconnecter">
    </p>
</form>

<?php

// Cloturer la requete

$prep->closeCursor();
$prep=NULL;
die;
?>
</body>