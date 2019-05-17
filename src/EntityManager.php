<?php
require_once 'DataBaseManager.php';

require_once 'User.php';

/**
 * Class EntityManager
 */
class EntityManager
{
    /** @var object DataBaseManager */
    private $dbConnect;

    /** @var object */
    private $entity;

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
     * @param integer $desiredId
     * @return object|null
     */
    public function findById($desiredId)
    {
        $this->entity->setId($desiredId);

        $queryResult = $this->dbConnect->select('*')->from($this->entity->getTable())->where('id = ' . $this->entity->getId())->getQuery()->prepare()->execute();

        if (!$queryResult) {
            return NULL;
        }

        return $this->setProperties($queryResult);
    }

    /**
     * @param $queryResult
     * @return object
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

        return $this->entity;
    }
}