<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\User;
use ApiBundle\Service\EntityManager\EntityManager;
use ApiBundle\Service\UserManager;
use ApiBundle\Routing\Response;

/**
 * Class UserController
 * @package ApiBundle\Controller
 */
class UserController
{
    /**
     * @return object UserManager
     */
    private function getUserManager()
    {
        return new UserManager();
    }

    /**
     * @param integer $id
     * @return object Response
     */
    public function getUser($id)
    {
        /** @var User $user */
        $user = $this->getUserManager()->getEntityManager()->findById($id);
        if($user) {
            return new Response(Response::HTTP_OK, json_encode($user->toArray()));
        }

        return new Response(Response::HTTP_NOT_FOUND, "User is not found!");
    }

    /**
     * @param integer $id
     * @return object Response
     */
    public function deleteUser($id)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getUserManager()->getEntityManager();
        $desiredUser = $entityManager->findById($id);
        if($desiredUser) {
            $entityManager->deleteById($id);

            return new Response(Response::HTTP_NO_CONTENT);
        }

        return new Response(Response::HTTP_NOT_FOUND, "User is not found!");
    }

    /**
     * @param array $fieldValues
     * @return object Response
     */
    public function createUser($fieldValues)
    {
        return new Response(Response::HTTP_CREATED, json_encode($this->getUserManager()->create($fieldValues)->toArray()));
    }

    /**
     * @param integer $id
     * @param array $fieldValues
     * @return object Response
     */
    public function updateUser($id, $fieldValues)
    {
        /** @var User $desiredUser */
        $desiredUser = $this->getUserManager()->update($id, $fieldValues);
        if($desiredUser) {
            return new Response(Response::HTTP_OK, json_encode($desiredUser->toArray()));
        }

        return new Response(Response::HTTP_NOT_FOUND, "User is not found!");
    }
}