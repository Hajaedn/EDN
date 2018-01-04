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
    $result = $prep->fetch();
    $result_nb = $prep->rowCount();

    if ($result_nb == 0){
        //pas enregistré
        header("Location: index.php?err=2");

    }elseif ($result_nb > 1){
        //problème conflit bdd

        header("Location: index.php?err=3");
    }elseif ($result_nb == 1) {

        //l'utilisateur est inscrit en base de données
        // Connexion Ok : Vérif droits
        $_SESSION['sess_actif']=$result[('usr_enable')];

        if ($_SESSION['sess_actif']=1) {
            $actif='oui';
            var_dump($_SESSION['sess_actif']);
            die;
        } else {
            // Utilisateur désactivé
            $actif='non';
            header("Location: index.php?err=4");
        }
        $_SESSION['sess_droits']=$result[('usr_right')];
        if ($_SESSION['sess_droits']='admin') {
            $admin='oui';
        }
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
//    var_dump($_SESSION['sess_droits']);
//    die;
    header("Location: suite.php");
?>

