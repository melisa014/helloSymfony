<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


/**
 * 
 */
class UserRepository extends EntityRepository
{
    /**
     * Получить Id пользователя по имени пользователя
     * 
     * @param string $username
     * 
     * @return int
     */
    public function findOneByUsername(string $username): int
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT * FROM AppBundle:Users WHERE username=' .$username
            )
            ->getResult();
    }
}
