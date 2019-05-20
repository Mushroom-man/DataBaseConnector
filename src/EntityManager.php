<?php
require_once 'DataBaseManager.php';

require_once 'User.php';

require_once 'Role.php';

class EntityManager
{
    /** @var object DataBaseManager */
    private $dbConnect;

    /** @var object */
    private $entity;

    private $newId;

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
        $this->newId = $desiredId;
        $queryResult = $this->dbConnect->select('*')->from($this->entity->getTable())->where('id = ' . $desiredId)->getQuery()->prepare()->execute();

        if (!$queryResult) {
            $this->dbConnect = new DataBaseManager();

            return NULL;
        }

        return $this->setProperties($queryResult);
    }

    /**
     * @param array $queryResult
     * @return object
     */
    private function setProperties($queryResult)
    {
        $arrClassMethods = get_class_methods(get_class($this->entity));

        foreach ($queryResult[0] as $key => $value) {
            $key = ucfirst($key);
            foreach ($arrClassMethods as $methodName) {
                if ($methodName == 'set' . $key && $value !== NULL) {
                    $this->entity->$methodName($value);
                }
            }
        }

        return $this->entity;
    }

    /**
     * @return object
     */
    public function save()
    {
        $entity = $this->entity;

        $arrClassMethods = get_class_methods(get_class($this->entity));

        $columnList = [];

        $insertValues = [];

        $query = $this->dbConnect;

        if ($entity->getId() !== NULL) {

            $query->update($entity->getTable());

            foreach ($arrClassMethods as $value) {
                $what = substr($value, 0, 3);
                if ($what == 'get' && $value !== 'getTable') {
                    $column = substr($value, 3);
                    $column = lcfirst($column);
                    $query->set($column . ' = ' . "'" . $entity->$value() . "'");
                }
            }
            $query->limit(1)->getQuery()->prepare()->execute();
        } else {
            $entity->setId($this->newId);
            foreach ($arrClassMethods as $value) {
                $what = substr($value, 0, 3);
                if ($what == 'get' && $value !== 'getTable') {
                    $column = substr($value, 3);
                    $column = lcfirst($column);
                    $columnList[] = $column;
                    $insertValues[] = $entity->$value();
                }
            }
            $query->insert($entity->getTable(), implode(', ', $columnList))->values(implode(', ', $insertValues));
            $query->getQuery()->prepare()->execute();
        }

        return $this;//->entity;
    }
}