<?php

namespace ApiBundle\Controller;

use ApiBundle\Service\EntityManager\EntityManager;

use ApiBundle\Entity\User;

/**
 * Class UserController
 * @package ApiBundle\Controller
 */
class UserController
{
    /**
     * @param integer $id
     * @return object EntityManager
     */
    public function getUser($id)
    {
        $entityManager = new EntityManager();

        $entityManager->setEntityName(User::class);

        $entityManager->findById($id);

        return $entityManager;
    }
}