<?php
ini_set('display_errors', true);

require_once "config.php";

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
$pdo = new pdo($dsn, $user, $password, $opt);


$tristesse = array (
    'Choix1' => 'Pas bien grave !',
    'Choix2' => 'C\'est dommage !',
    'Choix3' => 'C\'est la fin du monde !');
$option_fiche = array (
    'Choix_option1' => 'Visualiser',
    'Choix_option2' => 'Editer',
    'Choix_option3' => 'Supprimer');


function CheckId($id) {
    global $pdo;
    $query = 'SELECT * FROM users WHERE usr_id =:usr_id';
    $prep = $pdo->prepare($query);
    $prep->bindValue(':usr_id', $id, PDO::PARAM_INT);
    $prep->execute();
    $result = $prep->fetch();
    $result_nb = $prep->rowCount();
    if ($result_nb != 1) {
        return false;
    }

    return true;
}

//
function CheckRight($my_id, $id_change) {
    // Profil admin
    if ($_SESSION['sess_droits']=='admin'){
        return true;
    // ou Modif sur son propre profil
    }elseif ($my_id==$id_change) {
        return true;
    }
    return false;
}
