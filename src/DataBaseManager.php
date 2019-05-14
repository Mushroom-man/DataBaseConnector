<?php
require_once 'ConfigParser.php';

class DataBaseManager
{
    /** @var PDO  */
    private $connection;

    /** @var string */
    private $fields;

    /**
     * @var
     */
    private $table;

    /**
     * @var
     */
    private $where;

    /**
     * @var
     */
    private $orderBy;

    /**
     * @var
     */
    private $stmt;

    /**
     * @var
     */
    private $insertValues;

    /**
     * @var
     */
    private $updatedFields;

    /**
     *
     */
    const SELECT_TYPE = 0;

    /**
     *
     */
    const INSERT_TYPE = 1;

    /**
     *
     */
    const UPDATE_TYPE = 2;

    /**
     *
     */
    const DELETE_TYPE = 3;

    /**
     * @var
     */
    private $requestType;

    /**
     * DataBaseManager constructor.
     */
    public function __construct()
    {
        $className = 'ConfigParser';
        $className::parseData();
        $dsn = "mysql:host=" . ConfigParser::$parsedData["db_host"] . ";dbname=" . ConfigParser::$parsedData["db_name"] . ";charset=" . ConfigParser::$parsedData["charset"];
        //$dsn = "mysql:host=127.0.0.1;dbname=MyTest;charset=utf8";
        $user = ConfigParser::$parsedData["db_user"];
        $pass = ConfigParser::$parsedData["db_password"];
        $this->connection = new PDO($dsn, $user, $pass);
    }

    /**
     *
     */
    public function getConnection()
    {
        $this->connection;
    }

    /**
     * @param $fields
     * @param null $alias
     * @return $this
     */
    public function select($fields, $alias = NULL)
    {
        $this->requestType = self::SELECT_TYPE;

        if($alias) {
            $this->fields[] = $fields . ' AS ' . $alias;
        } else {
            $this->fields[] = $fields;
        }

        return $this;
    }

    /**
     * @param $table
     * @param null $alias
     * @return $this
     */
    public function from($table, $alias = NULL)
    {
        if ($alias) {
            $this->table[] = $table . ' ' . $alias;
        } else {
            $this->table[] = $table;
        }

        return $this;
    }

    /**
     * @param $conditions
     * @return $this
     */
    public function where($conditions)
    {
           $this->andWhere($conditions);

        return $this;
    }

    /**
     * @param $columnName
     * @param string $sortingDirection
     * @return $this
     */
    public function order_by($columnName, $sortingDirection = 'ASC')
    {
        $this->orderBy[] = $columnName . ' ' . $sortingDirection;

        return $this;
    }

    /**
     * @param $table
     * @param null $fields
     * @return $this
     */
    public function insert($table, $fields = NULL)
    {
        $this->requestType = self::INSERT_TYPE;

        $this->table .= $table;

        if ($fields) {
                $this->fields .= ' ' . '(' . $fields . ')';
        }

        return $this;
    }

    /**
     * @param $newData
     * @return $this
     */
    public function values($newData)
    {
        $this->insertValues .= '(' . $newData . ')';

        return $this;
    }

    /**
     * @return int
     */
    public function exec()
    {
        $result =  $this->connection->exec($this->stmt);

        return $result;
    }

    /**
     * @param $table
     * @return $this
     */
    public function update($table)
    {
        $this->requestType = self::UPDATE_TYPE;

        $this->table .= $table;

        return $this;
    }

    /**
     * @param $newConditions
     * @return $this
     */
    public function set($newConditions)
    {
        $this->updatedFields[] = $newConditions;

        return $this;
    }

    /**
     * @param $conditions
     * @param $conditionsOptions
     * @return $this
     */

    /**
     * @param $table
     * @return $this
     */
    public function delete($table)
    {
        $this->requestType = self::DELETE_TYPE;

        $this->table .= $table;

        return $this;
    }

    public function inWhere($conditions, $conditionsOptions)
    {
        $this->request .= ' WHERE ' . $conditions . ' IN ' . '(' . $conditionsOptions . ')';

        return $this;
    }

    /**
     * @param $conditions
     * @return $this
     */
    public function andWhere($conditions)
    {
        $this->where[] = $conditions;

        return $this;
    }

    /**
     * @return $this
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
     * @return $this
     */
    public function generateSelectQuery()
    {
        $this->stmt = 'SELECT ' . implode(', ', $this->fields) . ' FROM ' . implode(', ', $this->table);

        if($this->where) {
            $this->stmt .= ' WHERE ' . implode(' AND ', $this->where);
        }
        if($this->orderBy){
            $this->stmt .= ' ORDER BY ' . implode(', ', $this->orderBy);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function generateInsertQuery()
    {
        $this->stmt = 'INSERT INTO ' . $this->table . $this->fields . ' VALUES ' . $this->insertValues;

        return $this;
    }

    /**
     * @return $this
     */
    public function generateUpdateQuery()
    {
        $this->stmt = 'UPDATE ' . $this->table . ' SET ' . implode(', ', $this->updatedFields);

        if($this->where) {
            $this->stmt .= ' WHERE ' . implode(' AND ', $this->where);
        }

        return $this;
    }

    /**
     * @return $this
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
     * @return $this
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

    /**
     * @return array
     */
    public function getResult()
    {
        $this->getQuery();
        $result = $this->connection->query($this->request);

        return $result->fetchAll();
    }
}