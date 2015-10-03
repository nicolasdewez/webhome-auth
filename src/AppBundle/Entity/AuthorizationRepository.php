<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

/**
 * AuthorizationRepository.
 */
class AuthorizationRepository extends EntityRepository
{
    /**
     * @return ArrayCollection
     */
    public function getForAdmin()
    {
        $query = $this->createQueryBuilder('a')
            ->where('a.code LIKE \'%_ALL\'');

        return $query->getQuery()->execute();
    }

    /**
     * @return ArrayCollection
     */
    public function getForUser()
    {
        $query = $this->createQueryBuilder('a')
            ->where('a.code LIKE \'%_USER\'');

        return $query->getQuery()->execute();
    }
}
