<?php
require_once 'ma_lib.php';
session_start();

//vérification de la connexion
$pdo = new pdo($dsn, $user, $password, $opt);
$user = User::getFromDataBase($pdo, $_SESSION['id']);
if (empty($user)) {header("Location: index.php");}
?>
<html>
<head>
    <meta charset="utf-8" />
    <title>Titre de ma page 2</title>
</head>
<h1>Ma page 2</h1>
<br>
<?php
if (empty($_SESSION['login'])) {
    header("Location: index.php");
}
?>
A propos de la disparition du gars, vous pensez que : <b>
<?php
    echo $_POST['votre_avis'];
    $texte = $_POST['votre_avis'];
    $filepath = 'Remarques.txt';
    $monfichier = fopen($filepath, 'a+');
    if($monfichier === false) {
        throw new Exception('Unable to open file for read : ' . $filepath );
    }
    fwrite($monfichier, $texte . "\n");
    fclose($monfichier);
?>
</b>
<br>
Votre niveau d'implication :
<b>
<?php
    // var_dump($_POST);
    $key = $_POST['choix_tristesse'];
    echo $tristesse[$key];
?></b><br>
<form method="POST" action="upload.php" enctype="multipart/form-data">
    <!-- Le contenu du formulaire est à placer ici... -->
    <!-- input type="hidden" name="MAX_FILE_SIZE" value="10000" /> -->
    <input type="file" name="choix" value="Choisir le fichier" />
    <input type="submit"  value="Valider" />
</form>
</body>
</html>