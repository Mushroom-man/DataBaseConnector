<?php
require_once 'DataBaseManager.php';

require_once 'User.php';

class EntityManager
{
    private $dbConnect;

    private $oneEntity;

    private $desiredId;

    private $arrObjVars;

    public function __construct($className)
    {
      $this->dbConnect = new DataBaseManager();

      $this->oneEntity = new $className;

      $this->getProperties();
    }

    public function setEntityName($entityName)
    {
      $this->oneEntity->entityName = $entityName;

      return $this;
    }

    public function findById($desiredId)
    {
        $this->desiredId = $desiredId;

        $queryResult = $this->dbConnect->select('*')->from($this->oneEntity->table)->where('id = ' . $desiredId)->getQuery()->prepare()->execute();

        if ($queryResult == []) {
            try {
                throw new Exception("Does not exist in the database");
            } catch(Exception $e) {
                echo $e->getMessage();
            }
        } else {
            $this->setProperties($queryResult);
        }

        return $this;
    }

    public function getProperties()
    {
        $this->arrObjVars = get_object_vars($this->oneEntity);

        return $this;
    }

    public function setProperties($queryResult)
    {
        if (array_key_exists("id", $this->arrObjVars)) {
            $this->oneEntity->id = $queryResult[0]["id"];
        }
        if (array_key_exists("name", $this->arrObjVars) && $queryResult[0]["name"] !== NULL) {
            $this->oneEntity->name = $queryResult[0]["name"];
        }
        $this->dbConnect = new DataBaseManager();

        return $this;
    }
}