<?php

class DataBaseManager
{
    /** @var PDO  */
    private $connection;

    /** @var string */
    private $request;

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
    public function select($fields) {

        $this->request .= 'SELECT ' . $fields;

        return $this;
    }

    /**
     * @param $table
     * @param null $alias
     * @return $this
     */
    public function from($table, $alias = NULL) {

        if ($alias !== NULL) {

            $this->request .= ' FROM ' . $table . ' AS ' . $alias;

        } else {

            $this->request .= ' FROM ' . $table;

        };

        return $this;
    }

    /**
     * @param $conditions
     * @return $this
     */
    public function where($conditions) {

        $this->request .= ' WHERE ' . $conditions;

        return $this;

    }

    /**
     * @param $columnName
     * @param null $sortingDirection
     * @return $this
     */
    public function order_by($columnName, $sortingDirection = NULL){

        $this->request .= ' ORDER BY ' . $columnName . ' ' . $sortingDirection;

        return $this;

    }



    /**
     * @return array
     */
    public function getResult() {

       $result = $this->connection->query($this->request);

       return $result->fetchAll();
    }

}