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
<h1>Fiche utilisateur</h1>
<!-- Première ligne avec du code PHP -->
<?php


$user_login= $_POST['usr_login'];
$user_pwd=$_POST['usr_pwd'];
$user_name= $_POST['usr_name'];
$user_right= $_POST['usr_right'];
$user_create= $_POST['usr_create'];
$user_enable= $_POST['usr_enable'];

try {
    $pdo = new pdo($dsn, $user, $password, $opt);

    // Liste des Utilisateurs "actifs"

    $sql='INSERT INTO users(usr_login, usr_pwd, usr_name, usr_right, usr_create, usr_enable) 
            VALUES(:usr_login, :usr_pwd, :usr_name, :usr_right, :usr_create, :usr_enable)';
    $prep = $pdo->prepare($sql);
    $prep->bindParam('usr_login', $user_login, PDO::PARAM_STR);
    $prep->bindParam('usr_pwd', $user_pwd, PDO::PARAM_STR);
    $prep->bindParam('usr_name', $user_name, PDO::PARAM_STR);
    $prep->bindParam('usr_right', $user_right, PDO::PARAM_STR);
    $prep->bindParam('usr_create', $user_create, PDO::PARAM_INT);
    $prep->bindParam('usr_enable', $user_enable, PDO::PARAM_INT);
    $prep->execute();


} catch (PDOException $e) {
    die('Erreur : ' .$e->getMessage());
}
// if (empty($_SESSION['login'])) {
    header("Location: usr_list.php");
// }
?>

</body>