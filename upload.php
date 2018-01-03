<?php
    require_once 'ma_lib.php';
    session_start();
    if (empty($_SESSION['login'])) {
        header("Location: index.php");
    }
    // Tableau contenant les extensions autorisées.
    $extensions = array('.txt', '.php');
    // Limitation de la taille du fichier
    $max_file_size = 10000;
    // var_dump($_FILES);
    $file_size = filesize($_FILES['choix']['tmp_name']);
    if($file_size>$max_file_size)
    {
        die('Le fichier est trop gros ...');
    }
    // récupère la partie de la chaine à partir du dernier . pour connaître l'extension.
    $extension = strrchr($_FILES['choix']['name'], '.');
    //Ensuite on teste
    if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
    {
        echo  'Vous devez uploader un fichier de type txt ou php...';
        die();
    }
    $mydata = file_get_contents($_FILES['choix']['name'], true);
    echo '<pre>' . $mydata . '</pre>';

