<?php
/**
 * Created by PhpStorm.
 * User: Антоненко
 * Date: 19.02.2019
 * Time: 21:57
 */


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

        $this->request .= ' FROM ' . $table;

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