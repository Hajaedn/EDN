<?php

require_once 'ma_lib.php';

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

try {
    $user = User::connect($pdo, $_POST['my_id'], $_POST['my_pass']);
} catch (Exception $e) {

    die($e->getMessage());
}

header("Location: suite.php");