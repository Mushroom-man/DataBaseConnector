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
        $entityManager = new EntityManager();
        $entityManager->setEntityName(User::class);
        $desiredUser = $entityManager->findById($id);
        if($desiredUser) {
            $dataBaseAccess = new DataBaseManager();
            $dataBaseAccess->delete('user')->where('id =' . $id)->limit(1)->getQuery()->prepare()->execute();

            return new Response(Response::NO_CONTENT);
        }
        return new Response(Response::HTTP_NOT_FOUND, "User is not found!");
    }

    /**
     * @return object Response
     */
    public function createUser($incomingParams)
    {
        $newUser = new User();
        $classMethods = get_class_methods(get_class($newUser));
        foreach ($incomingParams as $field => $value) {
            foreach ($classMethods as $methodName) {
                if ($methodName == 'set' . ucfirst($field)) {
                    $newUser->$methodName($value);
                }
            }
        }
        $entityManager = new EntityManager();
        $entityManager->setEntityName(User::class);
        $user = $entityManager->save($newUser);

        return new Response(Response::CREATED, json_encode($user->toArray()));
    }

    /**
     * @param integer $id
     * @param array $fieldValues
     * @return object Response
     */
    public function updateUser($id, $fieldValues)
    {
        $entityManager = new EntityManager();
        $entityManager->setEntityName(User::class);
        $desiredUser = $entityManager->findById($id);
        $classMethods = get_class_methods(get_class($desiredUser));
        foreach ($fieldValues as $field => $value) {
            foreach ($classMethods as $methodName) {
                if ($methodName == 'set' . ucfirst($field)) {
                    $desiredUser->$methodName($value);
                }
            }
        }
        if($desiredUser) {
            $updatedUser = $entityManager->save($desiredUser);

            return new Response(Response::HTTP_OK, json_encode($updatedUser->toArray()));
        }
        return new Response(Response::HTTP_NOT_FOUND, "User is not found!");
    }
}