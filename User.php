<?php
require_once 'ma_lib.php';

abstract class DbEntity
{
    private $_dbPrimaryKeyValue;

    /** @return string */
    abstract public function getDbPrimaryKeyName();
    /** @return string */
    abstract public function getDbTableName();
    /** @return array */
    abstract public function getDbColumnsMapping();

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_dbPrimaryKeyValue;
    }

    public function setId($id){
        if(empty($id)){
            throw new Exception("Id is null or empty");
        }
        $this->_dbPrimaryKeyValue = $id;
    }

    /**
     * @param PDO $pdo
     * @throws Exception
     */
    public function saveInDatabase(PDO $pdo){
        //user infos are not set or empty
        foreach ($this->getDbColumnsMapping() as $attrName => $dbName) {
            if (empty($this->$attrName)) {
                throw new InvalidArgumentException($attrName." is not set or empty");
            }
        }

        if(empty($this->_dbPrimaryKeyValue)) {
            // Liste des Utilisateurs "actifs"

            $sql = 'INSERT INTO ' . $this->getDbTableName() . '(';

            $sql .= implode(',', array_values($this->getDbColumnsMapping()));

            $sql .= ') VALUES (';
            $sql .= implode(',',
                array_map(
                    function($v) {
                        return ':' . $v;
                    }, $this->getDbColumnsMapping()
                )
            );
            $sql .= ')';

            $prep = $pdo->prepare($sql);

            foreach($this->getDbColumnsMapping() as $attrName => $dbName) {
                $prep->bindParam($dbName, $this->$attrName, PDO::PARAM_STR);
            }

            $prep->execute();

            $this->_dbPrimaryKeyValue = $pdo->lastInsertId();
        } else {
            //TODO
            throw new Exception("pas encore implementé");
        }
    }

    /**
     * @param PDO $pdo
     * @throws Exception
     */
    public static function deleteInDataBase(PDO $pdo, User $user){

        // ensure primary key is not NULL
        if(empty($user->_dbPrimaryKeyValue)) {
           throw new Exception("Trying to delete user without it's primary key");
        }

        $query = "DELETE FROM {$user->getDbTableName()} WHERE {$user->getDbPrimaryKeyName()} =:id";
        $prep = $pdo->prepare($query);
        $prep->bindValue(':id', $user->_dbPrimaryKeyValue, PDO::PARAM_STR);
        $prep->execute();
    }


    public function getInfosFromDataBaseById(PDO $pdo, $id){
        $query = 'SELECT * FROM ' . $this->getDbTableName() . ' WHERE ' . $this->getDbPrimaryKeyName() . ' =:id';
        $prep = $pdo->prepare($query);
        $prep->bindValue(':id', $id, PDO::PARAM_STR);
        $prep->execute();
        $result = $prep->fetch();

        return $result;
    }

    public function getAttribute(PDO $pdo, $id, $attrNames){

        if(empty($id)){
            throw new InvalidArgumentException("Id is not set or empty");
        }

        foreach ($attrNames as $attrName) {
            if (empty($attrName)) {
                throw new InvalidArgumentException($attrName." is not set or empty");
            }
        }

        $query = 'SELECT \'';
        $query .= implode(', ', array_values($attrNames));
        $query .= ' WHERE '. $this->getDbPrimaryKeyName() . '=:id';


        $prep = $pdo->prepare($query);

        $prep->bindValue(':id', $id, PDO::PARAM_STR);
        $prep->execute();


        $attributesValue = $prep->fetch();

        return $attributesValue;
    }


}



class User extends DbEntity
{
    const RIGHTS_USER = 'user';
    const RIGHTS_ADMIN = 'admin';


    protected $_id;
    protected $_login;
    protected $_password;
    protected $_rights;
    protected $_creationDate;
    protected $_name;
    protected $_enable;



    public function getDbTableName()
    {
        return 'users';
    }

    public function getDbColumnsMapping()
    {
        return [
            '_login' => 'usr_login',
            '_password' => 'usr_pwd',
            '_name' => 'usr_name',
            '_rights' => 'usr_right',
            '_creationDate' => 'usr_create',
            '_enable' => 'usr_enable'
        ];
    }

    /**
     * @param $array
     */
    public function parseUserInfo($array){
        foreach($this->getDbColumnsMapping() as $attrName => $dbName) {
            $$attrName = $array[$dbName];
        }
        $this->setId($array[$this->getDbPrimaryKeyName()]);
    }

    public function getDbPrimaryKeyName()
    {
        return 'usr_id';
    }

    /**
     * @return array
     */
    public static function getRightsValues()
    {
        /*
         * self::$_champ_statique : accéder à un champ static de classe à l'intérieur de celle-ci
         * self::méthode_statique() : accéder à une méthode static de classe à l'intérieur de celle-ci
         */

        return [
            self::RIGHTS_USER,
            self::RIGHTS_ADMIN
        ];
    }

    public function setUserInfo($login,
                                $password,
                                $rights,
                                $name,
                                $enable,
                                $creationDate)
    {

        $this->_login = $login;
        $this->_password = $password;
        $this->setRights($rights);
        //$this->_creationDate = date('d/m/Y');
        $this->_name = $name;
        $this->_enable = $enable;
        $this->_creationDate = $creationDate;
        return $this;
    }



    /**
     * @param $rights
     * @return User
     */
    public function setRights($rights)
    {
        $rightsValues = self::getRightsValues();

        if(!in_array($rights, $rightsValues)) {
            throw new InvalidArgumentException("$rights is not a valid rights");
        }

        $this->_rights = $rights;

        return $this;
    }

    /**
     * @param PDO $pdo
     * @param $login
     * @param $password
     * @return User
     * @throws Exception
     */
    public static function connect(PDO $pdo, $login, $password){

        $query = 'SELECT * FROM users WHERE usr_login =:login AND usr_pwd =:password';
        $prep = $pdo->prepare($query);
        $prep->bindValue(':login', $login, PDO::PARAM_STR);
        $prep->bindValue(':password', $password, PDO::PARAM_STR);
        $prep->execute();

        $result = $prep->fetch();
        $result_nb = $prep->rowCount();

        if($result_nb != 1){
            throw new Exception("Invalid row count : ". $result_nb);
        }


        $user = new User();
        $user->parseUserInfo($result);

        session_start();
//      $_SESSION['connected_user'] = $user->getId();
        $_SESSION['id'] = $user->getId();
        $_SESSION['sess_actif']=$result[('usr_enable')];
    //l'utilisateur est inscrit en base de données
        $_SESSION['sess_droits']=$result[('usr_right')];
        $_SESSION['login']= $_POST['my_id'];
        $_SESSION['name']= $result['usr_name'];
        $_SESSION['id']= $result['usr_id'];

        return $user;
    }

    /**
     * @param PDO $pdo
     * @param $id
     * @return User
     */
    public static function checkId(PDO $pdo, $id) {

        $user = new User();

        $result = $user->getInfosFromDataBaseById($pdo, $id);

        if (empty($result)) {
            throw new Exception("Empty response");
        }

        $user->parseUserInfo($result);

        return $user;
    }


    public function disconnect() {
        unset($_SESSION['connected_user']);
    }

}

/*
 * Notes :
 * 1) Chaînage des méthodes possible grâce à "return $this;" en fin de méthode
 *
 *      $user = new User();
 *      $user->setId(1)->setRights('admin');
 *
 */
//
//
// ----------- TEST ------------
//
//$user->setUserInfo("napparait pas2", "m", User::RIGHTS_ADMIN, "matthieu besson", true);
//
//try {
//    //$user->saveInDatabase($pdo);//id créé et stocké dans la classe
//
//    //$user = new User();
//
//    $user = User::connect($pdo, "robert", "123");
//
//
//    //$user = User::getUserById($pdo, $id);
//
//} catch (Exception $e) {
//    die($e->getMessage());
//}
//
//try {
//    $user->deleteInDataBase($pdo);
//} catch (Exception $e) {
//    die($e->getMessage());
//}