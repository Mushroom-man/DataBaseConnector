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

    /** @var array*/
    private $checkSelect;

    /**
     * EntityManager constructor.
     * @param string $className
     */
    public function __construct($className)
    {
        $this->dbConnect = new DataBaseManager();

        $this->entity = $className;
    }

    /**
     * @param integer $desiredId
     * @return object|null
     */
    public function findById($desiredId)
    {
        $queryResult = $this->dbConnect->select('*')->from($this->entity->getTable())->where('id = ' . $desiredId)->getQuery()->prepare()->execute();
        $this->checkSelect = $queryResult;

        if (!$queryResult) {

            return NULL;
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

        if ($entity->getId()) {

            $query->update($entity->getTable());

            foreach ($arrClassMethods as $value) {
                $what = substr($value, 0, 3);
                if ($what == 'get' && $value !== 'getTable') {
                    $column = substr($value, 3);
                    $column = lcfirst($column);
                    $query->set($column . ' = ' . "'" . $entity->$value() . "'");
                }
            }
            $query->getQuery()->prepare()->execute();
        } else {
            foreach ($arrClassMethods as $value) {
                $what = substr($value, 0, 3);
                if ($what == 'get' && $value !== 'getTable') {
                    $column = substr($value, 3);
                    $column = lcfirst($column);
                    if($entity->$value()) {
                        $columnList[] = $column;
                        $insertValues[] = $entity->$value();
                    }
                }
            }
            $this->dbConnect = new DataBaseManager();

            $query = $this->dbConnect;

            $insertData = implode(" ', ' ", $insertValues);

            $query->insert($entity->getTable(), implode(', ', $columnList))->values("'" . $insertData . "'");

            $query->getQuery()->prepare()->execute();
        }

        return $this->entity;
    }
}