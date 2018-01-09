<?php
/**
 * Created by PhpStorm.
 * User: haja
 * Date: 09/01/18
 * Time: 11:45
 */

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