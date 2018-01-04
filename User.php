<?php
require_once 'ma_lib.php';

abstract class DbEntity
{
    /** @return string */
    abstract public function getDbPrimaryKey();
    /** @return string */
    abstract public function getDbTableName();
    /** @return array */
    abstract public function getDbColumnsMapping();

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

        if(empty($this->_id)) {
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

            $this->_id = $pdo->lastInsertId();
        } else {
            //TODO
            throw new Exception("pas encore implementé");
        }
    }
}

class User extends DbEntity
{
    const RIGHTS_USER = 'user';
    const RIGHTS_ADMIN = 'admin';

    private $_dbPrimaryKey;
    protected $_login;
    protected $_password;
    protected $_rights;
    protected $_creationDate;
    protected $_name;
    protected $_enable;

    private static $_toto = 'titi';

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

    public function getDbPrimaryKey()
    {
        return $this->_dbPrimaryKey;
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
        self::$_toto;
        return [
            self::RIGHTS_USER,
            self::RIGHTS_ADMIN
        ];
    }

    public function setUserInfo($login,
                                $password,
                                $rights,
                                $name,
                                $enable)
    {

        $this->_login = $login;
        $this->_password = $password;
        $this->setRights($rights);
        $this->_creationDate = date('d/m/Y');
        $this->_name = $name;
        $this->_enable = $enable;

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

    public function connect(User $user){
        $_SESSION['connected_user'] = $user->getId();
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



$user = new User();

$user->setUserInfo("matt", "m", "admin", "matthieu besson", true);
$user->saveInDatabase($pdo);