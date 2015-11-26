<?php

namespace AppBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class AuthorizationGroup.
 */
class AuthorizationGroup
{
    /** @var ArrayCollection */
    private $authorizations;

    public function __construct()
    {
        $this->authorizations = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getAuthorizations()
    {
        return $this->authorizations;
    }
}
