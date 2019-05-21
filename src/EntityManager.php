<?php
require_once 'DataBaseManager.php';

require_once 'User.php';

require_once 'Role.php';

class EntityManager
{
    /** @var object DataBaseManager */
    // private $dbConnect;
    /** @var object */
    private $entity;

    /**
     * EntityManager constructor.
     * @param string $className
     */
    public function __construct($className)
    {
        $this->entity = new $className();

    }

    /**
     * @param $desiredId
     * @return object|null
     */
    public function findById($desiredId)
    {
        $connectForSelect = new DataBaseManager();
        $queryResult = $connectForSelect->select('*')->from($this->entity->getTable())->where('id = ' . $desiredId)->getQuery()->prepare()->execute();
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

        $saveRequest = new DataBaseManager();
        foreach ($arrClassMethods as $value) {
            $what = substr($value, 0, 3);
            if ($what == 'get' && $value !== 'getTable') {
                $column = substr($value, 3);
                $column = lcfirst($column);
                $columnList[]= $column;
                $insertValues[$column] = $entity->$value();
            }
        }

        if ($entity->getId()) {
            $saveRequest->update($entity->getTable());
            $countColumns = count($columnList);

            for ($i = 0; $i < $countColumns; ++$i) {
                $saveRequest->set($columnList[$i] . ' = ' . "'" . $insertValues[$columnList[$i]] . "'");
            }
            $saveRequest->where('id = ' . $entity->getId())->limit(1)->getQuery()->prepare()->execute();
            var_dump($saveRequest);
        } else {
            $id = array_search('id', $columnList);
            unset($columnList[$id]);
            $insertColumns = implode(', ', $columnList);

            $saveRequest->insert($entity->getTable(), $insertColumns);

            unset($insertValues["id"]);
            foreach ($insertValues as $key => $value) {
                if($value == NULL){
                    $insertValues[$key] = "NULL";
                }
            }

            $saveRequest->values(implode(', ', $insertValues));
            $saveRequest->getQuery()->prepare()->execute();

            $insertedId = $saveRequest->lastInsertId()->prepare()->execute();

            $entity->setId($insertedId[0]["LAST_INSERT_ID()"]);
        }

        return $this;
    }
}