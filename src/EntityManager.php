<?php
require_once 'DataBaseManager.php';

require_once 'User.php';

class EntityManager
{
    private $entityName;

    private $id;

    private $name;

    private $dbConnect;

    public function __construct()
    {
      $this->dbConnect = new DataBaseManager();
    }

    public function setEntityName($entityName)
    {
      $this->entityName = $entityName;

      return $this;
    }

    public function findById($desiredId)
    {
        $userEntity = User::class;

        $queryResult = $this->dbConnect->select('*')->from($userEntity::$table)->where('id = ' . $desiredId)->getQuery()->prepare()->execute();

        $this->setProperties($queryResult);

        return $this;
    }

    public function setProperties($queryResult)
    {
        $this->id = $queryResult[0]["id"];

        $this->name = $queryResult[0]["name"];

        return $this;
    }
}