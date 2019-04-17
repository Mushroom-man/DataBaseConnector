<?php

class DataBaseManager
{
    /** @var PDO  */
    private $connection;

    /** @var string */
    private $request;

    private $arrRequest = [
        'from' => NULL,
        'where' => NULL,
        'andWhere' => NULL,
        'orderBy' => NULL
    ];

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
        $this->request .= 'SELECT ' . $fields;

        return $this;
    }

    /**
     * @param $table
     * @param null $alias
     * @return $this
     */
    public function from($table, $alias = NULL)
    {
        $this->arrRequest['from'] .= ' FROM ' . $table;

        if ($alias) {
            $this->request .=  ' AS ' . $alias;
        }

        return $this;
    }

    /**
     * @param $conditions
     * @return $this
     */
    public function where($conditions)
    {
        $this->arrRequest['where'] .= ' WHERE ' . $conditions;

        return $this;
    }

    /**
     * @param $columnName
     * @param string $sortingDirection
     * @return $this
     */
    public function order_by($columnName, $sortingDirection = 'ASC')
    {
        $this->arrRequest['orderBy'] .= ' ORDER BY ' . $columnName . ' ' . $sortingDirection;

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
        $this->arrRequest['andWhere'] .= ' AND ' . $conditions;

        return $this;
    }

    /**
     * @return array
     */
    public function getResult()
    {
        $arrToStr = implode(" ", $this->arrRequest);

        $this->request .= $arrToStr;

        $result = $this->connection->query($this->request);

        return $result->fetchAll();
    }
}