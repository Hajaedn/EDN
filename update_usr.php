<?php
require_once 'ma_lib.php';
session_start();

$pdo = new pdo($dsn, $user, $password, $opt);
$user_me = User::getFromDataBase($pdo, $_SESSION['id']);
if (empty($user_me)) {header("Location: index.php");}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Utilisateurs</title>
</head>
<body>
<h1>Fiche utilisateur</h1>
<!-- PHP avec la Commande SQL -->
<?php


class InvalidHttpPostArgumentException extends Exception
{}


try {
    if (empty($_POST['usr_id'])) {
        throw new InvalidHttpPostArgumentException('Bad usr_id value');
    }

    $user_id = $_POST['usr_id'];

    $user = User::getFromDataBase($pdo, $user_id);
    $user->setName($_POST['usr_name']);
    $user->setPassword($_POST['usr_pwd']);
    $user->setLogin($_POST['usr_login']);
    $user->setEnable($_POST['usr_enable']);
    $user->setRights($_POST['usr_right']);
    $user->setCreationDate($_POST['usr_create']);
    $user->saveInDatabase($pdo);


    if (CheckRight($_SESSION['id'], $user_id) == false) {
        throw new InvalidHttpPostArgumentException('Pas les droits');
    }


////    $pdo = new pdo($dsn, $user, $password, $opt);
//
//    // Mise à jour Utilisateur
//    $sql = 'UPDATE users set  usr_login = :usr_login, usr_pwd=:usr_pwd, usr_name=:usr_name, usr_right=:usr_right, usr_create=:usr_create, usr_enable=:usr_enable where usr_id=:id';
//    $prep = $pdo->prepare($sql);
//    $prep->bindParam('usr_login', $user_login, PDO::PARAM_STR);
//    $prep->bindParam('usr_pwd', $user_pwd, PDO::PARAM_STR);
//    $prep->bindParam('usr_name', $user_name, PDO::PARAM_STR);
//    $prep->bindParam('usr_right', $user_right, PDO::PARAM_STR);
//    $prep->bindParam('usr_create', $user_create, PDO::PARAM_INT);
//    $prep->bindParam('usr_enable', $user_enable, PDO::PARAM_INT);
//    $prep->bindParam('id', $user_id, PDO::PARAM_INT);
//    $prep->execute();
} catch(InvalidHttpPostArgumentException $e) {
    die($e->getMessage());
} catch (PDOException $e) {
    die($e->getMessage() . 'Erreur de base de donnée cachée');
} catch (Exception $e) {
    var_dump($e->getMessage());
    die('Autre erreur cachée');
}

header("Location: usr_list.php");

?>

</body>