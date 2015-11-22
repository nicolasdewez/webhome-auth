<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Ndewez\WebHome\CommonBundle\Service\Validator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class Password.
 */
class Password
{
    /** @var UserPasswordEncoderInterface */
    private $encoder;

    /** @var Doctrine */
    private $doctrine;

    /** @var Validator */
    private $validator;

    /**
     * @param Doctrine                     $doctrine
     * @param UserPasswordEncoderInterface $encoder
     * @param Validator                    $validator
     */
    public function __construct(Doctrine $doctrine, UserPasswordEncoderInterface $encoder, Validator $validator)
    {
        $this->encoder = $encoder;
        $this->doctrine = $doctrine;
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

        $user->setPassword($this->encoder->encodePassword($user, $user->getPassword()));
    }
}
