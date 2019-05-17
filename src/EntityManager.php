<?php
require_once 'DataBaseManager.php';

require_once 'User.php';

class EntityManager
{
    private $dbConnect;

    private $oneEntity;

    private $desiredId;

    public function __construct($className)
    {
      $this->dbConnect = new DataBaseManager();

      $this->oneEntity = new $className;
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

    public function setProperties($queryResult)
    {
        $arrObjVars = get_object_vars($this->oneEntity);

        $arrExistingProperties= array_intersect_key($queryResult[0], $arrObjVars);

        foreach ($arrExistingProperties as $key => $value) {
            if ($value !== NULL) {
                $this->oneEntity->$key = $value;
            }
        }
        $this->dbConnect = new DataBaseManager();

        return $this;
    }
}