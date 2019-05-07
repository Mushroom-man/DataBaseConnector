<?php

class DataBaseManager
{
    /** @var PDO  */
    private $connection;

    /** @var string */
    private $fields;
    /**
     * @var
     */
    private $from;
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
     * DataBase constructor.
     */
    public function __construct()
    {
        $dsn = "mysql:host=127.0.0.1;dbname=MyTest;charset=utf8";
        $user = 'root';
        $pass = 'LizardKing7';
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
     * @return $this
     */
    public function select($fields)
    {
        $this->fields .= $fields;

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
            $this->from[] = $table . ' ' . $alias;
        } else {
            $this->from[] = $table;
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
     * @param null $column
     * @return $this
     */
    public function insert($table, $column = NULL)
    {
        $this->request .= 'INSERT INTO ' . $table;

        if ($column) {
                $this->request .= ' ' . '(' . $column . ')';
        }

        return $this;
    }

    /**
     * @param $newData
     * @return $this
     */
    public function values($newData)
    {
        $this->request .= ' VALUES ' . '(' . $newData . ')';

        return $this;
    }

    /**
     * @return int
     */
    public function exec()
    {
        $result =  $this->connection->exec($this->request);

        return $result;
    }

    /**
     * @param $table
     * @return $this
     */
    public function update($table)
    {
        $this->request .= 'UPDATE ' . $table;

        return $this;
    }

    /**
     * @param $updatedColumn
     * @return $this
     */
    public function set($updatedColumn)
    {
        $this->request .= ' SET ' .  $updatedColumn;

        return $this;
    }

    /**
     * @param $conditions
     * @param $conditionsOptions
     * @return $this
     */
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
    public function prepare()
    {
        $strStmt = 'SELECT ' . $this->fields . ' FROM ' . implode(', ', $this->from) . ' WHERE ' .  implode(' AND ', $this->where) . ' ORDER BY ' . implode(', ', $this->orderBy);

        var_dump($strStmt);

        $this->stmt = $this->connection->prepare($strStmt);

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