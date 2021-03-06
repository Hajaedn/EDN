<?php
require_once 'ma_lib.php';
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Utilisateurs</title>
</head>
<body>
<h1>Fiche utilisateur</h1>
<!-- Première ligne avec du code PHP -->
<?php
    $key=$_GET["nom"];
    echo $key;

if (empty($_SESSION['login'])) {
    header("Location: index.php");
}

$pdo = new pdo($dsn, $user, $password, $opt);

// Liste des Utilisateurs "actifs"

$query = 'SELECT * FROM users WHERE usr_login =:usr_login';
$prep = $pdo->prepare($query);
$prep->bindValue(':usr_login', $key, PDO::PARAM_STR);
$prep->execute();
$result = $prep->fetch();
$enable = 'Off';
if ($result['usr_enable']) {
    $enable = 'Actif';
};
$type_prf = 'Utilisateur';
if ($result['usr_right']='admin') {
    $type_prf = 'Administrateur';
};
?>
<fieldset>
    <legend>Informations d'identification</legend>
    <b>Nom :  </b><a><?php echo $result['usr_name']; ?></a> <br><br>
    <b>Profil : </b><a><?php echo $result['usr_id'] ?></a><br><br>
    <b>Mot de passe : </b><a><?php echo $result['usr_pwd']; ?></a>
</fieldset><br>
<fieldset>
    <legend>Informations annexes</legend>
    <b>Date d'inscription : </b><a><?php echo strftime($result['usr_create']); ?></a><br><br>
    <b>Type de profil : </b><a><?php echo $type_prf; ?></a><br><br>
    <b>Profil actif : </b><a><?php echo $enable; ?></a>

</fieldset>



</body>