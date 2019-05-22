<?php
require_once 'DataBaseManager.php';

require_once 'User.php';

require_once 'Role.php';

class EntityManager
{
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
        $data = $connectForSelect->select('*')->from($this->entity->getTable())->where('id = ' . $desiredId)->getQuery()->prepare()->execute();
        if (!$data) {
            return NULL;
        }
        return $this->setProperties($data);
    }

    /**
     * @param $data
     * @return object
     */
    private function setProperties($data)
    {
        $classMethods = get_class_methods(get_class($this->entity));
        foreach ($data[0] as $key => $value) {
            $key = ucfirst($key);
            foreach ($classMethods as $methodName) {
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
    public function save($entity)
    {
        $this->entity = $entity;

        $classMethods = get_class_methods(get_class($this->entity));

        $entityFieldList = [];

        $entityFieldValues = [];

        foreach ($classMethods as $methodName) {
            $methodType = substr($methodName, 0, 3);
            if ($methodType == 'get' && $methodName !== 'getTable') {
                $fieldName = substr($methodName, 3);
                $fieldName = lcfirst($fieldName);
                $entityFieldList[] = $fieldName;
                $entityFieldValues[$fieldName] = $entity->$methodName();
            }
        }

        if ($entity->getId()) {

            return $this->update($entityFieldList, $entityFieldValues, $entity);
        } else {

        return $this->insert($entityFieldList, $entityFieldValues, $entity);
        }
    }

    private function update($entityFieldList, $entityFieldValues, $entity)
    {
        $saveRequest = new DataBaseManager();
        $saveRequest->update($entity->getTable());
        $countedEntityFields = count($entityFieldList);
        for ($i = 0; $i < $countedEntityFields; ++$i) {
            $saveRequest->set($entityFieldList[$i] . ' = ' . "'" . $entityFieldValues[$entityFieldList[$i]] . "'");
        }
        $saveRequest->where('id = ' . $entity->getId())->limit(1)->getQuery()->prepare()->execute();

        return $entity;
    }

    private function insert($entityFieldList, $entityFieldValues, $entity)
    {
        $saveRequest = new DataBaseManager();
        $id = array_search('id', $entityFieldList);
        unset($entityFieldList[$id]);
        $insertColumns = implode(', ', $entityFieldList);
        $saveRequest->insert($entity->getTable(), $insertColumns);
        unset($entityFieldValues["id"]);
        foreach ($entityFieldValues as $field => $value) {
            if($value == NULL){
                $entityFieldValues[$field] = "NULL";
            }
        }

        $saveRequest->values(implode(', ', $entityFieldValues));

        $saveRequest->getQuery()->prepare()->execute();

        $insertedId = $saveRequest->lastInsertId()->prepare()->execute();

        $entity->setId($insertedId[0]["LAST_INSERT_ID()"]);

        return $entity;
    }
}