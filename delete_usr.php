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

if (empty($_SESSION['login'])) {
    header("Location: index.php");
}

try {
    $user->deleteInDataBase($pdo);
} catch (Exception $e) {
    die($e->getMessage());
}

//
//try {
//    $pdo = new pdo($dsn, $user, $password, $opt);
//
//    // Liste des Utilisateurs "actifs"
//
//    $query = 'DELETE FROM users WHERE usr_id =:usr_id';
//    $prep = $pdo->prepare($query);
//    $prep->bindValue(':usr_id', $key, PDO::PARAM_STR);
//    $prep->execute();
//} catch (PDOException $e) {
//    die('Erreur : ' .$e->getMessage());
//}
header("Location: usr_list.php");
?>

</body>