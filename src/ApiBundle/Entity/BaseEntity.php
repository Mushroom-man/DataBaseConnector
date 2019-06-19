<?php
namespace ApiBundle\Entity;

class BaseEntity
{
    /** @var integer */
    private $id;

    /** @var string */
    protected $table;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }
}