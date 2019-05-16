<?php
require_once 'DataBaseManager.php';

require_once 'User.php';

class EntityManager
{
    private $dbConnect;

    private $oneEntity;

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
        $queryResult = $this->dbConnect->select('*')->from($this->oneEntity->table)->where('id = ' . $desiredId)->getQuery()->prepare()->execute();

        $this->setProperties($queryResult);

        return $this;
    }

    public function setProperties($queryResult)
    {
        $this->oneEntity->id = $queryResult[0]["id"];

        $this->oneEntity->name = $queryResult[0]["name"];

        return $this;
    }
}