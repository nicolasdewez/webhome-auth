<?php

namespace AppBundle\Model;

use AppBundle\Entity\Authorization;

/**
 * Class AuthorizationGranted.
 */
class AuthorizationGranted
{
    /** @var Authorization */
    private $authorization;

    /** @var bool */
    private $granted;

    /**
     * @param Authorization $authorization
     * @param bool          $granted
     */
    public function __construct(Authorization $authorization, $granted = false)
    {
        $this->authorization = $authorization;
        $this->granted = $granted;
    }

    /**
     * @return Authorization
     */
    public function getAuthorization()
    {
        return $this->authorization;
    }

    /**
     * @param Authorization $authorization
     *
     * @return AuthorizationGranted
     */
    public function setAuthorization($authorization)
    {
        $this->authorization = $authorization;

        return $this;
    }

    /**
     * @return bool
     */
    public function isGranted()
    {
        return $this->granted;
    }

    /**
     * @param bool $granted
     *
     * @return AuthorizationGranted
     */
    public function setGranted($granted)
    {
        $this->granted = $granted;

        return $this;
    }
}
