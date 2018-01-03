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
}
else{
    $pdo = new pdo($dsn, $user, $password, $opt);

    // Liste des Utilisateurs "actifs"

    $query = 'SELECT * FROM users WHERE usr_login =:usr_login AND usr_pwd =:usr_pwd';
    $prep = $pdo->prepare($query);
    $prep->bindValue(':usr_login', $_POST['my_id'], PDO::PARAM_STR);
    $prep->bindValue(':usr_pwd', $_POST['my_pass'], PDO::PARAM_STR);
    $prep->execute();
    $result = $prep->rowCount();


    if ($result == 0){
        //pas enregistré
        header("Location: index.php?err=2");

    }elseif ($result > 1){
        //problème conflit bdd

    }elseif ($result == 1) {
        //l'utilisateur est inscrit en base de données

    }


}
////
////if ($passw != ($identity . '123')) {
////    // Erreur mot de passe
////    $_SESSION['validate']='non';
////    session_destroy();
////    header("Location: index.php?err=2");
////    exit;
//}
// It's OK !!
    session_start();
// var_dump($identity . " " . $passw);
// die;
    $_SESSION['login']=$identity;
    $_SESSION['validate']='ok';
    header("Location: suite.php");
?>

