<?php

namespace ApiBundle\Controller;

use ApiBundle\Routing\Response;
use ApiBundle\Service\DataBaseManager;
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

    /**\
     * @param integer $id
     * @return object Response
     */
    public function deleteUser($id)
    {
        $entityManager = new EntityManager();
        $entityManager->setEntityName(User::class);
        $desiredUser = $entityManager->findById($id);
        if($desiredUser) {
            $dataBaseAccess = new DataBaseManager();
            $dataBaseAccess->delete('user')->where('id =' . $id)->limit(1)->getQuery()->prepare()->execute();

            return new Response(204);
        }

        return new Response(Response::HTTP_NOT_FOUND, "User is not found!");
    }
}