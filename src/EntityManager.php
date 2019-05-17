<?php
require_once 'DataBaseManager.php';

require_once 'User.php';

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
     * @param $desiredId
     * @return object|null
     */
    public function findById($desiredId)
    {
        $queryResult = $this->dbConnect->select('*')->from($this->entity->getTable())->where('id = ' . $desiredId)->getQuery()->prepare()->execute();

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
        $arrClassMethods = get_class_methods(User::class);

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
}