<?php
ini_set('display_errors', true);
$host = '127.0.0.1';
$db = 'mydb';
$user = 'root';
$password = '';
$charset='utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt=[
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
];
$pdo = new pdo($dsn, $user, $password, $opt);
$tristesse = array (
    'Choix1' => 'Pas bien grave !',
    'Choix2' => 'C\'est dommage !',
    'Choix3' => 'C\'est la fin du monde !');
$option_fiche = array (
    'Choix_option1' => 'Visualiser',
    'Choix_option2' => 'Editer',
    'Choix_option3' => 'Supprimer');


