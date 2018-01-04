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
$user->setId(1)->setRights('admin');