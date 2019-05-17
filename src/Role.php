<?php
require_once 'BaseEntity.php';

class Role extends BaseEntity
{
    /** @var string */
    private $table = 'role';

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