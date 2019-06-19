<?php

namespace ApiBundle\Service;

use PDO;

use ApiBundle\ConfigParser;


class DataBaseManager
{
    /** @var PDO  */
    private $connection;

    /** @var string */
    private $fields;

    /** @var array */
    private $table;

    /** @var array */
    private $where;

    /** @var array */
    private $orderBy;

    /** @var string */
    private $stmt;

    /** @var string */
    private $insertValues;

    /** @var string */
    private $joinTable;

    /** @var string */
    private $compareField;

    /** @var array */
    private $updatedFields;

    /** @var integer*/
    private $quantityRecord;

    const SELECT_TYPE = 0;

    const INSERT_TYPE = 1;

    const UPDATE_TYPE = 2;

    const DELETE_TYPE = 3;

    /** @var integer */
    private $requestType;

    /**
     * DataBaseManager constructor.
     */
    public function __construct()
    {
        ConfigParser::parseData();
        $dsn = "mysql:host=" . ConfigParser::$parsedData["db_host"] . ";dbname=" . ConfigParser::$parsedData["db_name"] . ";charset=" . ConfigParser::$parsedData["charset"];
        $user = ConfigParser::$parsedData["db_user"];
        $pass = ConfigParser::$parsedData["db_password"];
        $this->connection = new PDO($dsn, $user, $pass);
    }

    public function getConnection()
    {
        $this->connection;
    }

    public function lastInsertId()
    {
        $this->stmt = 'SELECT LAST_INSERT_ID()';

        return $this;

    }

    /**
     * @param string $fields
     * @param null $alias
     * @return object $this
     */
    public function select($fields, $alias = NULL)
    {
        $this->requestType = self::SELECT_TYPE;

        if($alias) {
            $this->fields = $fields . ' AS ' . $alias;
        } else {
            $this->fields = $fields;
        }

        return $this;
    }

    /**
     * @param string $table
     * @param null $alias
     * @return object $this
     */
    public function from($table, $alias = NULL)
    {
        if ($alias) {
            $this->table = $table . ' ' . $alias;
        } else {
            $this->table = $table;
        }

        return $this;
    }

    /**
     * @param string $conditions
     * @return object $this
     */
    public function where($conditions)
    {
           $this->andWhere($conditions);

        return $this;
    }

    /**
     * @param string $columnName
     * @param string $sortingDirection
     * @return object $this
     */
    public function order_by($columnName, $sortingDirection = 'ASC')
    {
        $this->orderBy[] = $columnName . ' ' . $sortingDirection;

        return $this;
    }

    /**
     * @param string $table
     * @param null $fields
     * @return object $this
     */
    public function insert($table, $fields = NULL)
    {
        $this->requestType = self::INSERT_TYPE;

        $this->table = $table;

        if ($fields) {
                $this->fields .= ' ' . '(' . $fields . ')';
        }

        return $this;
    }

    /**
     * @param string $newData
     * @return object $this
     */
    public function values($newData)
    {
        $this->insertValues .= '(' . $newData . ')';

        return $this;
    }

    /**
     * @param string $table
     * @return object $this
     */
    public function update($table)
    {
        $this->requestType = self::UPDATE_TYPE;

        $this->table = $table;

        return $this;
    }

    /**
     * @param string $newConditions
     * @return object $this
     */
    public function set($newConditions)
    {
        $this->updatedFields[] = $newConditions;

        return $this;
    }

    /**
     * @param string $table
     * @return object $this
     */
    public function delete($table)
    {
        $this->requestType = self::DELETE_TYPE;

        $this->table .= $table;

        return $this;
    }

    /**
     * @param string $conditions
     * @return object $this
     */
    public function andWhere($conditions)
    {
        $this->where[] = $conditions;

        return $this;
    }

    public function limit($quantityRecord)
    {
        $this->quantityRecord = $quantityRecord;

        return $this;

    }

    /**
     * @return object $this
     */
    public function getQuery()
    {
        switch ($this->requestType) {
            case self::SELECT_TYPE:
                 $this->generateSelectQuery();
                 break;
            case self::INSERT_TYPE:
                 $this->generateInsertQuery();
                 break;
            case self::UPDATE_TYPE:
                 $this->generateUpdateQuery();
                 break;
            case self::DELETE_TYPE:
                 $this->generateDeleteQuery();
                 break;
        }

        return $this;
    }

    /**
     * @return object $this
     */
    public function generateSelectQuery()
    {
        $this->stmt = 'SELECT ' . $this->fields . ' FROM ' . $this->table;

        if($this->joinTable){
            $this->stmt .= ' INNER JOIN ' . $this->joinTable . ' ON ' . $this->compareField;
        }
        if($this->where) {
            $this->stmt .= ' WHERE ' . implode(' AND ', $this->where);
        }
        if($this->orderBy){
            $this->stmt .= ' ORDER BY ' . implode(', ', $this->orderBy);
        }
        if($this->quantityRecord){
            $this->stmt .= ' LIMIT ' . $this->quantityRecord;
        }

        return $this;
    }

    /**
     * @return object $this
     */
    public function generateInsertQuery()
    {
        $this->stmt = 'INSERT INTO ' . $this->table;
        if($this->fields) {
            $this->stmt .= $this->fields;
        }
        $this->stmt .= ' VALUES ' . $this->insertValues;

        return $this;
    }

    /**
     * @return object $this
     */
    public function generateUpdateQuery()
    {
        $this->stmt = 'UPDATE ' . $this->table . ' SET ' . implode(', ', $this->updatedFields);

        if($this->where) {
            $this->stmt .= ' WHERE ' . implode(' AND ', $this->where);
        }
        if($this->quantityRecord) {
            $this->stmt .= ' LIMIT ' . $this->quantityRecord;
        }

        return $this;
    }

    /**
     * @return object $this
     */
    public function generateDeleteQuery()
    {
        $this->stmt = 'DELETE FROM ' . $this->table;
        if($this->where) {
            $this->stmt .= ' WHERE ' . implode(' AND ', $this->where);
        }

        return $this;
    }

    /**
     * @param string $table
     * @param string $field
     * @param null $alias
     * @return object $this
     */
    public function innerJoin($table, $field, $alias = NULL)
    {
        if ($alias) {
            $this->joinTable = $table . ' ' . $alias;
        } else {
            $this->joinTable = $table;
        }

        $this->compareField = $field;

        return $this;
    }

    /**
     * @return object $this
     */
    public function prepare()
    {
        $this->stmt = $this->connection->prepare($this->stmt);

        return $this;
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        $this->stmt->execute();

        $result = $this->stmt->fetchAll();

        return $result;
    }
}