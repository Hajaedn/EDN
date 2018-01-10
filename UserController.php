<?php
/**
 * Created by PhpStorm.
 * User: matthieu
 * Date: 10/01/18
 * Time: 13:54
 */

class UserController
{
    /**
     * Print login page
     * @throws Exception
     */
    public function login(){
        ?>
        <h1>Identification</h1>
        <!-- Première ligne avec du code PHP -->
        Identifiez vous :
        <form method="post" action="index.php?controller=user&action=dologin">
            <p>
                <input type="text" name="my_id"/><br>
                <input type="password" name="my_pass"/>
                <?php
                $err = isset($_GET['err']) ? $_GET['err'] : 0;
                if ($err == 1) {
                    echo('Saisie obligatoire !');
                } else if ($err == 2) {
                    echo('Mot de passe invalide !');
                } else if ($err == 3) {
                    echo('Profil corrompu !');
                } else if ($err == 4) {
                    echo('Profil désactivé !');
                } else if ($err != 0) {
                    throw new Exception('Valeur invalide pour err');
                }

                //        switch ($err) {
                //            case 0:
                //                echo "En attente de saisie";
                //                break;
                //            case 1:
                //                echo "";
                //                break;
                //            case 2:
                //                echo "";
                //                break;
                //            default:
                //        throw new Exception('Valeur invalide pour err');
                //        }
                ?>

                <br><br><input type="submit" value="Valider">
            </p>
        </form>
        <?php
    }

    public function doLogin(){

        global $config;

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

        if (empty($_POST['my_id']) || (empty($_POST['my_pass']))) {
        // Erreur paramètres non saisis
        $_SESSION['validate']='non';
        session_destroy();
        header("Location: index.php?err=1");
        exit;
        }

        $pdo = new pdo($dsn, $user, $password, $opt);

        // Liste des Utilisateurs "actifs"

        try {
        $user = User::connect($pdo, $_POST['my_id'], $_POST['my_pass']);
        } catch (Exception $e) {

        die($e->getMessage());
        }

        header("index.php?controller=user&action=suite");
    }

    public function listUsr($pdo){
        $user = User::getFromDataBase($pdo, $_SESSION['id']);
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
    }
}