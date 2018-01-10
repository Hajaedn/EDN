<?php

require_once 'ma_lib.php';
include 'header.php';

$userController = new UserController();
try {
    $userController->login();
} catch (Exception $e) {
    die($e->getMessage());
}

include 'footer.php';
?>