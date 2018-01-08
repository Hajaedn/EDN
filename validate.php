<?php

require_once 'config.php';
// echo $_POST['my_id'];
// echo $_POST['my_pass'];
$host = $config['host'];
$db = $config['db'];
$user = $config['user'];
$password = $config['password'];
$charset = $config['charset'];
$dsn = $config['dsn'];

$opt=[
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
];

if (empty($_POST['my_id']) || (empty($_POST['my_pass']))) {
    // Erreur paramètres non saisis
    $_SESSION['validate']='non';
    session_destroy();
    header("Location: index.php?err=1");
    exit;
}

$pdo = new pdo($dsn, $user, $password, $opt);

// Liste des Utilisateurs "actifs"

$query = 'SELECT * FROM users WHERE usr_login =:usr_login AND usr_pwd =:usr_pwd';
$prep = $pdo->prepare($query);
$prep->bindValue(':usr_login', $_POST['my_id'], PDO::PARAM_STR);
$prep->bindValue(':usr_pwd', $_POST['my_pass'], PDO::PARAM_STR);
$prep->execute();
$result = $prep->fetch();
$result_nb = $prep->rowCount();

$err = false;

if ($result_nb == 0){
    //pas enregistré
    $err = 2;
}elseif ($result_nb > 1){
    //problème conflit bdd
    $err = 3;
}elseif ($result_nb == 1 && $result['usr_enable']!=1) {
    // Utilisateur désactivé
    $err = 4;
}

if ($err !== false) {
    header("Location: index.php?err={$err}");
    exit;
}

// It's OK !!
session_start();
$_SESSION['sess_actif']=$result[('usr_enable')];
//l'utilisateur est inscrit en base de données
$_SESSION['sess_droits']=$result[('usr_right')];
$_SESSION['login']= $_POST['my_id'];
$_SESSION['name']= $result['usr_name'];
$_SESSION['id']= $result['usr_id'];
header("Location: suite.php");