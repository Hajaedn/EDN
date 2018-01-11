<?php

require_once 'ma_lib.php';



class User extends DbEntity
{
    const RIGHTS_USER = 'user';
    const RIGHTS_ADMIN = 'admin';


    protected $_login;
    protected $_password;
    protected $_rights;
    protected $_creationDate;
    protected $_name;
    protected $_enable;

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->_login;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * @return mixed
     */
    public function getRights()
    {
        return $this->_rights;
    }

    /**
     * @return mixed
     */
    public function getCreationDate()
    {
        return $this->_creationDate;
    }

    /**
     * @return mixed
     */
    public function getCreationDateForDisplay()
    {
        //change date format
        $myDateTime = DateTime::createFromFormat('Y-m-d', $this->_creationDate);
        $create = $myDateTime->format('d-m-Y');
        return $create;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @return mixed
     */
    public function getEnable()
    {
        return $this->_enable;
    }



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

    public function canEdit($id) {
        return $this->getRights() == User::RIGHTS_ADMIN || $this->getId() == $id;
    }

    /**
     * @param $array
     * @throws Exception
     */
    public function parseUserInfo($array){
        foreach($this->getDbColumnsMapping() as $attrName => $dbName) {
            $this->$attrName = $array[$dbName];
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
        $this->setName($name);
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
     * @param name
     * @return User
     */
    public function setName($name)
    {

        if(!isset($name)) {
            throw new InvalidArgumentException("Empty name is not a valid name");
        }

        $this->_name = $name;

        return $this;
    }

    /**
     * @param login
     * @return User
     */
    public function setLogin($login)
    {

        if(!isset($login)) {
            throw new InvalidArgumentException("Empty login is not a valid login");
        }

        $this->_login= $login;

        return $this;
    }


    /**
     * @param password
     * @return User
     */
    public function setPassword($password)
    {

        if(!isset($password)) {
            throw new InvalidArgumentException("Empty password is not a valid password");
        }

        $this->_password= $password;

        return $this;
    }

    /**
     * @param enable
     * @return User
     */
    public function setEnable($enable)
    {

        if (! in_array($enable, [1, 0]))
        {
            throw new InvalidArgumentException("Enable must be a boolean value");
        }

        $this->_enable= $enable;

        return $this;
    }

    /**
     * @param creation date
     * @return User
     */
    public function setCreationDate($creationDate)
    {

        if($creationDate==0) {
            throw new InvalidArgumentException("Date mandatory");
        }

        $this->_creationDate= $creationDate;

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

//        session_start();// done in index.php
//      $_SESSION['connected_user'] = $user->getId();
        $_SESSION['id'] = $user->getId();
        $_SESSION['sess_actif']= $user->getEnable();

        $_SESSION['sess_droits']=$user->getRights();
        $_SESSION['login']= $user->getLogin();
        $_SESSION['name']= $user->getName();

        return $user;
    }

    /**
     * @param PDO $pdo
     * @param $id
     * @return User
     * @throws Exception
     */
    public static function getFromDataBase(PDO $pdo, $id) {

        $user = new User();

        $result = $user->getInfosFromDataBaseById($pdo, $id);

        if (empty($result)) {
            throw new Exception("Empty response");
        }

        $user->parseUserInfo($result);

        return $user;
    }


    public function disconnect() {
        //unset($_SESSION['connected_user']);
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