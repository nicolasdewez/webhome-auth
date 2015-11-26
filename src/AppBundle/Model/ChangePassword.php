<?php

namespace AppBundle\Model;

use AppBundle\Validator\Constraints as AppAssert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ChangePassword.
 *
 * @AppAssert\PasswordNotContainsUsername(fieldPassword="newPassword")
 * @AppAssert\CheckCurrentPassword
 */
class ChangePassword
{
    /** @var UserInterface */
    private $user;

    /** @var string */
    private $password;

    /**
     * @var string
     *
     * @Assert\Length(min=5)
     */
    private $newPassword;

    /**
     * @param UserInterface $user
     */
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return ChangePassword
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }

    /**
     * @param string $newPassword
     *
     * @return ChangePassword
     */
    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    /**
     * Method added to use PasswordNotContainsUsername constraint
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->user->getUsername();
    }

    /**
     * Method added to use CheckCurrentPassword constraint
     *
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }
}
