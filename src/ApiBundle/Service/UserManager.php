<?php

namespace ApiBundle\Service;

use ApiBundle\Entity\User;
use ApiBundle\Service\EntityManager\EntityManager;

/**
 * Class UserManager
 * @package ApiBundle\Service
 */
class UserManager
{
    /**
     * @param integer $id
     * @param array $fieldValues
     * @return object
     */
    public function update($id, $fieldValues)
    {
        $entityManager = $this->getEntityManager();
        $desiredUser = $entityManager->findById($id);
        if($desiredUser) {
            $desiredUser = $this->setRequiredFieldValues($fieldValues, $desiredUser);
            return $entityManager->save($desiredUser);
        }

        return $desiredUser;
    }

    /**
     * @param array $fieldValues
     * @return object mixed
     */
    public function create($fieldValues)
    {
        $newUser = new User();
        $newUser = $this->setRequiredFieldValues($fieldValues, $newUser);
        return $this->getEntityManager()->save($newUser);
    }

    /**
     * @return object EntityManager
     */
    public function getEntityManager()
    {
        $entityManager = new EntityManager();
        $entityManager->setEntityName(User::class);

        return $entityManager;
    }

    /**
     * @param array $fieldValues
     * @param object $user
     * @return object mixed
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
}