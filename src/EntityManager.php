<?php
require_once 'DataBaseManager.php';

class EntityManager
{
    private $entityName;

    private $userId;

    private $userName;

    public function setEntityName($entityName)
    {
      $this->entityName = $entityName;

      return $this;
    }

    public function findById($desiredId)
    {
        $dataBaseManager = new DataBaseManager();

        $queryResult = $dataBaseManager->select('*')->from('user')->where('id = ' . $desiredId)->getQuery()->prepare()->execute();

        $this->setProperties($queryResult);

        return $this;
    }

    public function setProperties($queryResult)
    {
        $this->userId = $queryResult[0]["id"];

        $this->userName = $queryResult[0]["name"];

        return $this;
    }
}