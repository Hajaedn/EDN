<?php

require_once 'ma_lib.php';
include 'header.php';
session_start();

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

//if (empty($_POST['my_id']) || (empty($_POST['my_pass']))) {
//    // Erreur paramètres non saisis
//    $_SESSION['validate']='non';
//    session_destroy();
//    header("Location: index.php?err=1");
//    exit;
//}

$pdo = new pdo($dsn, $user, $password, $opt);

if(isset($_GET['controller'], $_GET['action'])){
    $controller = $_GET['controller'];
    $action = $_GET['action'];
}


$userController = new UserController();
$userController->setPdo($pdo);

try {

    //contrôles sur variables
    if(isset($controller, $action)){

//        // choix de l'action à effectuer sur le UserController
//        switch($action){
//            case 'dologin':
//                $userController->doLogin();
//                break;
//
//            case 'listUsr':
//                $userController->listUsr($pdo);
//                break;
//
//            default:
//                $userController->login();
//        }

        // recover controller name
        $controllerName = $controller . 'Controller';

//        $$controllerName->$action();// exact method name is passed as 'action'

        $$controllerName($action);


    }
    else{
        $userController->login();
    }

} catch (Exception $e) {
    die($e->getMessage());
}

include 'footer.php';
