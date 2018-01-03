<?php
require_once 'ma_lib.php';
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Utilisateur</title>
</head>
<body>
<h1>Nouvel utilisateur</h1>

<!-- Saisie des infos pour un nouvel utilisateur -->
<!-- Première ligne avec du code PHP -->
<?php
    $pdo = new pdo($dsn, $user, $password, $opt);
?>


<form method="post" action="create_usr.php">
    <p>
        <br>
    <fieldset>
        <legend>Informations d'identification</legend>
        <b>Nom :  </b><input type="text" name="usr_name"/><br><br>
        <b>Profil : </b><input type="text" name="usr_login"/><br><br>
        <b>Mot de passe : </b><input type="text" name="usr_pwd"/><br><br>
    </fieldset><br>
    <fieldset>
        <legend>Informations annexes</legend>
        <b>Date d'inscription : </b><input type="date" name="usr_create"/><br><br>
        <b>Type de profil : </b>
            <select name="usr_right">
                    <option value="admin"> Administrateur</option>
                    <option value="user"> Utilisateur</option>
                </select><br><br>
        <b>Profil actif : </b>
            <a>
                <input type="radio" name="usr_enable" value=1 checked> Oui
                <input type="radio" name="usr_enable" value=0> Non<br>
            </a>
    </fieldset>
        <br><br><input type="submit" value="Valider">
    </p>
</form>
</body>