<?php
namespace ApiBundle\Entity;

class User extends BaseEntity
{
    /** @var string */
    private $name;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->table = 'user';
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