<?php
require_once 'ma_lib.php';
session_start();

//vérification de la connexion
$pdo = new pdo($dsn, $user, $password, $opt);
$user = User::checkId($pdo, $_SESSION['id']);
if (empty($user)) {header("Location: index.php");}

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
$idASupprimer=$_GET["id"];

if (empty($_SESSION['login'])) {
    header("Location: index.php");
}

try {
    $user = User::checkId($pdo, $idASupprimer);
    User::deleteInDataBase($pdo, $user);
} catch (Exception $e) {
    die($e->getMessage());
}

header("Location: usr_list.php");
?>

</body>