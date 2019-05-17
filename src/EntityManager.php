<?php
require_once 'DataBaseManager.php';

require_once 'User.php';

class EntityManager
{
    /** @var object DataBaseManager */
    private $dbConnect;

    /** @var object */
    private $entity;

    /** @var integer */
    private $desiredId;

    /**
     * EntityManager constructor.
     * @param string $className
     */
    public function __construct($className)
    {
      $this->dbConnect = new DataBaseManager();

      $this->entity = new $className;
    }

    /**
     * @param string $entityName
     * @return object $this
     */
    public function setEntityName($entityName)
    {
      $this->entity->entityName = $entityName;

      return $this;
    }

    /**
     * @param integer $desiredId
     * @return object $this|null
     */
    public function findById($desiredId)
    {
            $this->desiredId = $desiredId;

            $queryResult = $this->dbConnect->select('*')->from($this->entity->table)->where('id = ' . $desiredId)->getQuery()->prepare()->execute();

            if (!$queryResult) {

                return NULL;
            } else {
                $this->setProperties($queryResult);
        }

        return $this;
    }

    /**
     * @param array $queryResult
     * @return object $this
     */
    private function setProperties($queryResult)
    {
        $arrObjVars = get_object_vars($this->entity);

        $arrExistingProperties= array_intersect_key($queryResult[0], $arrObjVars);

        foreach ($arrExistingProperties as $key => $value) {
            if ($value !== NULL) {
                $this->entity->$key = $value;
            }
        }
        $this->dbConnect = new DataBaseManager();

        return $this;
    }
}