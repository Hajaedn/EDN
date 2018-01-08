<?php
require_once 'ma_lib.php';
session_start();

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


$user_login= $_POST['usr_login'];
$user_pwd=$_POST['usr_pwd'];
$user_name= $_POST['usr_name'];
$user_right= $_POST['usr_right'];
$user_create= $_POST['usr_create'];
$user_enable= $_POST['usr_enable'];

$user = new User();

$user->setUserInfo($user_login, $user_pwd, $user_right, $user_name, $user_enable, $user_create);

try {
    $user->saveInDatabase($pdo);
} catch (Exception $e) {
    die($e->getMessage());
}


header("Location: usr_list.php");

?>

</body>