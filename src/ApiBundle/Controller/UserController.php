<?php

namespace ApiBundle\Controller;

use ApiBundle\Routing\Response;
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
     * @return array|object Response
     */
    public function getUser($id)
    {
        $entityManager = new EntityManager();
        $entityManager->setEntityName(User::class);
        $user = $entityManager->findById($id);
        if($user) {
            return new Response(200, json_encode($user->toArray()));
        }

        return new Response(Response::HTTP_NOT_FOUND, "User is not found!");
    }
}