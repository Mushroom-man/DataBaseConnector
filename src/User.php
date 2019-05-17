<?php
require_once 'BaseEntity.php';

class User extends BaseEntity
{
    /** @var string */
    private $table = 'user';

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param string $table
     */
    public function setTable($table)
    {
        $this->table = $table;
    }
}