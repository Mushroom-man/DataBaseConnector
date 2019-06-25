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
     * @return object EntityManager
     */
    private function getUserEntityManager()
    {
        $entityManager = new EntityManager();
        $entityManager->setEntityName(User::class);

        return $entityManager;
    }

    /**
     * @param array $fieldValues
     * @param object $user
     * @return mixed
     */
    private function setRequiredFieldValues($fieldValues, $user)
    {
        $classMethods = get_class_methods(get_class($user));
        foreach ($fieldValues as $field => $value) {
            foreach ($classMethods as $methodName) {
                if ($methodName == 'set' . ucfirst($field)) {
                    $user->$methodName($value);
                }
            }
        }
        return $user;
    }

    /**
     * @param integer $id
     * @return array|object Response
     */
    public function getUser($id)
    {
        $user = $this->getUserEntityManager()->findById($id);
        if($user) {
            return new Response(Response::HTTP_OK, json_encode($user->toArray()));
        }
        return new Response(Response::HTTP_NOT_FOUND, "User is not found!");
    }

    /**\
     * @param integer $id
     * @return object Response
     */
    public function deleteUser($id)
    {
        $entityManager = $this->getUserEntityManager();
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
        $newUser = new User();
        $newUser = $this->setRequiredFieldValues($fieldValues, $newUser);
        $newUser = $this->getUserEntityManager()->save($newUser);

        return new Response(Response::HTTP_CREATED, json_encode($newUser->toArray()));
    }

    /**
     * @param integer $id
     * @param array $fieldValues
     * @return object Response
     */
    public function updateUser($id, $fieldValues)
    {
        $entityManager = $this->getUserEntityManager();
        $desiredUser = $entityManager->findById($id);
        $desiredUser = $this->setRequiredFieldValues($fieldValues, $desiredUser);
        if($desiredUser) {
            $updatedUser = $entityManager->save($desiredUser);

            return new Response(Response::HTTP_OK, json_encode($updatedUser->toArray()));
        }
        return new Response(Response::HTTP_NOT_FOUND, "User is not found!");
    }
}