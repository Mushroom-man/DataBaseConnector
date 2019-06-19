<?php
namespace ApiBundle\Entity;

class Role extends BaseEntity
{
    /** @var string */
    private $name;

    /**
     * Role constructor.
     */
    public function __construct()
    {
        $this->table = 'role';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}