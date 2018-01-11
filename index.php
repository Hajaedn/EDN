<?php

require_once 'ma_lib.php';
include 'header.php';
session_start();

if(isset($_GET['controller'], $_GET['action'])){
    $controller = $_GET['controller'];
    $action = $_GET['action'];
}


$userController = new UserController();


try {

    //contrôles sur variables
    if(isset($controller, $action)){

        // choix de l'action à effectuer sur le UserController
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

        $$controllerName->$action();// exact method name is passed as 'action'


    }
    else{
        $userController->login();
    }

} catch (Exception $e) {
    die($e->getMessage());
}

include 'footer.php';
