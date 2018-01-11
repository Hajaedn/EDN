<?php
/**
 * Created by PhpStorm.
 * User: matthieu
 * Date: 10/01/18
 * Time: 13:54
 */

/**
 * Class UserController
 */
class UserController
{

    private $pdo;

    /**
     * @return mixed
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    /**
     * @param mixed $pdo
     */
    public function setPdo($pdo)
    {
        $this->pdo = $pdo;
    }


    public function __invoke($action)
    {
        // TODO: Implement __invoke() method.

        if(method_exists($this, $action)){
            return $this->$action();
        }

        throw new Exception("Trying to invoke inexistant controller method");
    }

    public function suite(){
        echo 'suite';
    }

    /**
     * Print login page
     * @throws Exception
     */
    public function login(){
        ?>
        <h1>Identification</h1>
        <!-- Première ligne avec du code PHP -->
        Identifiez vous :
        <form method="post" action="index.php?controller=user&action=doLogin">
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

//        global $config;
//
//        $host = $config['host'];
//        $db = $config['db'];
//        $user = $config['user'];
//        $password = $config['password'];
//        $charset = $config['charset'];
//        $dsn = $config['dsn'];
//
//        $opt=[
//        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//        PDO::ATTR_EMULATE_PREPARES => false
//        ];

        if (empty($_POST['my_id']) || (empty($_POST['my_pass']))) {
        // Erreur paramètres non saisis
        $_SESSION['validate']='non';
        session_destroy();
        header("Location: index.php?err=1");
        exit;
        }

//        $pdo = new pdo($dsn, $user, $password, $opt);
//
        // Liste des Utilisateurs "actifs"

        try {
        $user = User::connect($this->getPdo(), $_POST['my_id'], $_POST['my_pass']);
        } catch (Exception $e) {

        die($e->getMessage());
        }

        header("Location: index.php?controller=user&action=listUsr");
    }

    public function listUsr(){

        // Liste des Utilisateurs "actifs"

        $query = 'SELECT * FROM users';
        $prep = $this->pdo->prepare($query);
        $prep->execute();
        $arrAll = $prep->fetchAll();

        $prep->closeCursor();
        $prep = null;

        $user = User::getFromDataBase($this->getPdo(), $_SESSION['id']);

        echo $this->renderView(
            "user_list.php",
            array(
                'users' => $arrAll,
                'current_user' => $user
            )
        );
    }

    public function viewUsr(){

        $Id=$_GET['Id'];
        $action=$_GET['modAct'];
        try {
            $userToSee = User::getFromDataBase($this->getPdo(), $Id);
        } catch (Exception $e) {
            //pas connecté ou problème de connexion bdd
            die($e->getMessage());
        }

        echo $this->renderView(
            "usr_fiche_edit.php",
            array(
                'userToSee' => $userToSee,
                'modAct' => $action
            )
        );
    }

    protected function renderView($template, array $data = array()) {
        extract($data, EXTR_SKIP);
        ob_start();
        require "templates/{$template}";
        return ob_get_clean();
    }
}