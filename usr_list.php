<?php
require_once 'ma_lib.php';
session_start();
$pdo = new pdo($dsn, $user, $password, $opt);
//var_dump($_SESSION['id']);
//die;
$user = User::getFromDataBase($pdo, $_SESSION['id']);
if (empty($user)) {header("Location: index.php");}
require_once 'header.php';
?>

<h1>Liste des utilisateurs</h1>

<a>Vous êtes : </a><?php echo $_SESSION['name'] ?>

<?php
//$pdo = new pdo($dsn, $user, $password, $opt);

// Liste des Utilisateurs "actifs"

$query = 'SELECT * FROM users';
$prep = $pdo->prepare($query);
$prep->execute();

$arrAll=$prep->fetchAll();

?>
<p>Créer un utilisateur <a href="usr_new.php">Création</a><br></p>

<!-- Tableau : Liste des utilisateurs -->
<div class="container">
    <div class="row">
        <div class="col">
            <table class="table">
                <!-- Ligne titre -->
                <tr>
                    <th> Utilisateur </th>
                    <th> Profil </th>
                    <th> Mot de passe </th>
                    <th> Droits </th>
                    <th> Date de création </th>
                    <th> Actif O/N </th>
                    <th> Action </th>
                </tr>
                <?php
                foreach($arrAll as $ligne) {
                    $name = $ligne['usr_name'];
                    $id = $ligne['usr_id'];
                    $login = $ligne['usr_login'];
                    $pwd = $ligne['usr_pwd'];
                    $right = $ligne['usr_right'];


                    $create = $ligne['usr_create'];

                    //change date format
                    $myDateTime = DateTime::createFromFormat('Y-m-d', $create);
                    $create = $myDateTime->format('d-m-Y');

                    $enable = 'off';
                    if ($ligne['usr_enable']) {
                        $enable = 'actif';
                    };
                    ?>
                    <tr>
                        <td><?= $name ?></td>
                        <td><?= $login ?></td>
                        <td><?= $pwd ?></td>
                        <td align='center'><?= $right ?></td>
                        <td align='center'><?= $create ?></td>
                        <td align='center'><?= $enable ?></td>
                        <td>
                            <div class="dropdown show">
                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" href='usr_fiche_view.php?id=<?= $id ?>&action=voir'>Voir</a>
                                    <?php if (($_SESSION['sess_droits'] == 'admin') || ($_SESSION['id']==$id)){ ?>
                                        <a class="dropdown-item" href="usr_fiche_edit.php?nom=<?= $id ?>">Edit</a>
                                        <a class="dropdown-item" href='usr_fiche_view.php?id=<?= $id ?>&action=suppr'>Delete</a>
                                    <?php } ?>
                                </div>
                            </div>


                        </td>
                    </tr>

                    <?php
                }
                ?>
            </table>
        </div>
    </div>
</div>


<form method="post" action="disconnect.php">
    <p>
        <br><br><input type="submit" value="Déconnecter">
    </p>
</form>

<?php

// Cloturer la requete

$prep->closeCursor();
$prep=NULL;
die;
?>

</body>


