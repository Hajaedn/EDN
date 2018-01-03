<?php

// echo $_POST['my_id'];
// echo $_POST['my_pass'];
$host = '127.0.0.1';
$db = 'mydb';
$user = 'haja';
$password = 'haja';
$charset='utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt=[
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
];


if (empty($_POST['my_id']) or (empty($_POST['my_pass']))) {
    // Erreur paramètres non saisis
    $_SESSION['validate']='non';
    session_destroy();
    header("Location: index.php?err=1");
    exit;
} else {
    $identity = $_POST['my_id'];
    $passw = $_POST['my_pass'];
}
if ($passw != ($identity . '123')) {
    // Erreur mot de passe
    $_SESSION['validate']='non';
    session_destroy();
    header("Location: index.php?err=2");
    exit;
}
// It's OK !!
    session_start();
// var_dump($identity . " " . $passw);
// die;
    $_SESSION['login']=$identity;
    $_SESSION['validate']='ok';
    header("Location: suite.php");
?>

