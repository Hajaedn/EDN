<?php

class User
{
    const RIGHTS_USER = 'user';
    const RIGHTS_ADMIN = 'admin';

    private $_id;
    private $_login;
    private $_password;
    private $_rights;

    private static $_toto = 'titi';

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

    /**
     * @param int $id
     * @return User
     */
    public function setId($id)
    {
        //contrôle de l'argument
        if(!is_int($id)) {
            throw new InvalidArgumentException('Id is not an integer');
        }

        $this->_id = $id;

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
}

$user = new User();

$user->setId(1)->setRights('admin');//chaînage des méthodes possible grâce à "return $this;" en fin de méthode