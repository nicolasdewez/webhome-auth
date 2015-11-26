<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Ndewez\WebHome\CommonBundle\Service\Validator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class Password.
 */
class Password
{
    /** @var EntityManagerInterface */
    private $manager;

    /** @var UserPasswordEncoderInterface */
    private $encoder;

    /** @var Validator */
    private $validator;

    /**
     * @param EntityManagerInterface       $manager
     * @param UserPasswordEncoderInterface $encoder
     * @param Validator                    $validator
     */
    public function __construct(EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, Validator $validator)
    {
        $this->manager = $manager;
        $this->encoder = $encoder;
        $this->validator = $validator;
    }

    /**
     * @param User   $user
     * @param string $originalPassword
     */
    public function encodePassword(User $user, $originalPassword = null)
    {
        if (null === $user->getPassword()) {
            $user->setPassword($originalPassword);

            return;
        }

        $user->initSalt();
        $user->setPassword($this->encoder->encodePassword($user, $user->getPassword()));
    }

    /**
     * @param User   $user
     * @param string $password
     */
    public function changePassword(User $user, $password)
    {
        $user->setPassword($password);
        $this->encodePassword($user);
        $this->manager->flush();
    }
}
