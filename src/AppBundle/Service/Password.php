<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Ndewez\WebHome\CommonBundle\Service\Validator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class Password.
 */
class Password
{
    /** @var UserPasswordEncoderInterface */
    private $encoder;

    /** @var Validator */
    private $validator;

    /**
     * @param UserPasswordEncoderInterface $encoder
     * @param Validator                    $validator
     */
    public function __construct(UserPasswordEncoderInterface $encoder, Validator $validator)
    {
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

        $user->setPassword($this->encoder->encodePassword($user, $user->getPassword()));
    }
}
