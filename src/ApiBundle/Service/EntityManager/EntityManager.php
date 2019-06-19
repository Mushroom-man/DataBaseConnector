<?php

namespace ApiBundle\Service\EntityManager;

use ApiBundle\Service\DataBaseManager;

class EntityManager
{
    /** @var object */
    private $entity;

    /**
     * @param string $className
     */
    public function setEntityName($className)
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
     * @param object $entity
     * @return object
     */
    public function save($entity)
    {
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
        }

        return $this->insert($entityFieldList, $entityFieldValues, $entity);
    }

    /**
     * @param array $entityFieldList
     * @param array $entityFieldValues
     * @param object $entity
     * @return object
     */
    private function update($entityFieldList, $entityFieldValues, $entity)
    {
        $dataBaseManager = new DataBaseManager();
        $dataBaseManager->update($entity->getTable());

        $countedEntityFields = count($entityFieldList);
        for ($i = 0; $i < $countedEntityFields; ++$i) {
            $dataBaseManager->set($entityFieldList[$i] . ' = ' . "'" . $entityFieldValues[$entityFieldList[$i]] . "'");
        }

        $dataBaseManager->where('id = ' . $entity->getId())->limit(1)->getQuery()->prepare()->execute();

        return $entity;
    }

    /**
     * @param array $entityFieldList
     * @param array $entityFieldValues
     * @param object $entity
     * @return object
     */
    private function insert($entityFieldList, $entityFieldValues, $entity)
    {
        $id = array_search('id', $entityFieldList);
        unset($entityFieldList[$id]);
        $insertColumns = implode(', ', $entityFieldList);
        unset($entityFieldValues["id"]);
        $entityFieldValues = implode("', '", $entityFieldValues);

        $dataBaseManager = new DataBaseManager();
        $dataBaseManager->insert($entity->getTable(), $insertColumns);
        $dataBaseManager->values("'" . $entityFieldValues . "'");
        $dataBaseManager->getQuery()->prepare()->execute();

        $insertedId = $dataBaseManager->lastInsertId()->prepare()->execute();
        $entity->setId($insertedId[0]["LAST_INSERT_ID()"]);

        return $entity;
    }
}