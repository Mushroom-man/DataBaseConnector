<?php
require_once 'DataBaseManager.php';

require_once 'User.php';

class EntityManager
{
    private $dbConnect;

    private $entityObj;

    private $desiredId;

    public function __construct($className)
    {
      $this->dbConnect = new DataBaseManager();

      $this->entityObj = new $className;
    }

    public function setEntityName($entityName)
    {
      $this->entityObj->entityName = $entityName;

      return $this;
    }

    public function findById($desiredId)
    {
        try {
            throw new Exception("Does not exist in the database");
        } catch(Exception $e) {

            $this->desiredId = $desiredId;

            $queryResult = $this->dbConnect->select('*')->from($this->entityObj->table)->where('id = ' . $desiredId)->getQuery()->prepare()->execute();

            if ($queryResult == []) {
                echo $e->getMessage();
            } else {
                $this->setProperties($queryResult);
            }
        }

        return $this;
    }

    private function setProperties($queryResult)
    {
        $arrObjVars = get_object_vars($this->entityObj);

        $arrExistingProperties= array_intersect_key($queryResult[0], $arrObjVars);

        foreach ($arrExistingProperties as $key => $value) {
            if ($value !== NULL) {
                $this->entityObj->$key = $value;
            }
        }
        $this->dbConnect = new DataBaseManager();

        return $this;
    }
}