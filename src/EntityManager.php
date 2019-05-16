<?php
require_once 'DataBaseManager.php';

require_once 'User.php';

class EntityManager
{
    private $dbConnect;

    private $userEntity;

    public function __construct()
    {
      $this->dbConnect = new DataBaseManager();

      $this->userEntity = new User();
    }

    public function setEntityName($entityName)
    {
      $this->userEntity->entityName = $entityName;

      return $this;
    }

    public function findById($desiredId)
    {
        $queryResult = $this->dbConnect->select('*')->from($this->userEntity->table)->where('id = ' . $desiredId)->getQuery()->prepare()->execute();

        $this->setProperties($queryResult);

        return $this;
    }

    public function setProperties($queryResult)
    {
        $this->userEntity->id = $queryResult[0]["id"];

        $this->userEntity->name = $queryResult[0]["name"];

        return $this;
    }
}