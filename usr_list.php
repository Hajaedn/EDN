<?php
require_once 'ma_lib.php';
session_start();
$pdo = new pdo($dsn, $user, $password, $opt);
//var_dump($_SESSION['id']);
//die;
$user = User::getFromDataBase($pdo, $_SESSION['id']);
if (empty($user)) {
    header("Location: index.php");
}
require_once 'header.php';
?>
<div class="jumbotron jumbotron-fluid">
    <div class="container">
        <h1>Liste des utilisateurs</h1>
        <a>Vous êtes : </a><?php echo $_SESSION['name'] ?>
        <?php
        //$pdo = new pdo($dsn, $user, $password, $opt);

        // Liste des Utilisateurs "actifs"

        $query = 'SELECT * FROM users';
        $prep = $pdo->prepare($query);
        $prep->execute();
        $arrAll = $prep->fetchAll();

        $prep->closeCursor();
        $prep = null;

        ?>
        <a href="disconnect.php">Déconnexion</a>
    </div>
</div>


<!-- Tableau : Liste des utilisateurs -->
<div class="container">
    <div class="row">
        <div class="col">
            <p>
                <a href="usr_new.php" class="btn btn-primary">Créer un utilisateur </a>
            </p>
            <table class="table table-dark">
                <!-- Ligne titre -->
                <thead class="thead-light">
                <tr>
                    <th> Utilisateur</th>
                    <th> Profil</th>
                    <th> Mot de passe</th>
                    <th> Droits</th>
                    <th> Date de création</th>
                    <th> Actif O/N</th>
                    <th> Action</th>
                </tr>
                </thead>
                <?php
                foreach ($arrAll as $ligne) {
                    $name = $ligne['usr_name'];
                    $id = $ligne['usr_id'];
                    $login = $ligne['usr_login'];
                    $pwd = $ligne['usr_pwd'];
                    $right = $ligne['usr_right'];

                    $userInLine = new User();
                    $userInLine->parseUserInfo($ligne);

                    $create = $userInLine->getCreationDate();

                    //change date format
                    $myDateTime = DateTime::createFromFormat('Y-m-d', $create);
                    $create = $myDateTime->format('d-m-Y');

                    $enable = 'off';
                    if ($ligne['usr_enable']) {
                        $enable = 'actif';
                    };

                    $isMe = false;
                    if ($user == $userInLine) {
                        $isMe = true;
                    }
// <span style="font-style:italic;">
                    ?>
                    <tr <?php if ($isMe) {
                        echo 'class="bg-primary"';
                    }; ?> >
                        <td><?= $userInLine->getName(); ?></td>
                        <td><?= $userInLine->getLogin(); ?></td>
                        <td><?= $userInLine->getPassword(); ?></td>
                        <td align='center'><?= $userInLine->getRights(); ?></td>
                        <td align='center'><?= $create; ?></td>
                        <td align='center'><?= $enable ?></td>
                        <td>
                            <div class="dropdown show">
                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                   id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                   aria-expanded="false">

                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item"
                                       href='usr_fiche_view.php?id=<?= $id ?>&action=voir'>Voir</a>
                                    <?php
                                    $autorized = false;
                                    if (($user->getRights() == User::RIGHTS_ADMIN) && !($user->getId() == $id)) {
                                        $autorized = true;
                                    }

                                    if ($autorized) {
                                        echo '<a class="dropdown-item" href=';
                                        echo '"usr_fiche_edit.php?nom=' . $id . '"';
                                        echo '>Edit</a>';
                                    }

                                    if ($autorized) {
                                        echo '<a class="dropdown-item" href=';
                                        echo '"usr_fiche_view.php?id=' . $id . '&action=suppr"';
                                        echo '>Delete</a>';
                                    }
                                    ?>
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

<?php

?>

</body>


