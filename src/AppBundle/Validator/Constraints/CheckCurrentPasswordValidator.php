<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class CheckCurrentPasswordValidator.
 */
class CheckCurrentPasswordValidator extends ConstraintValidator
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($object, Constraint $constraint)
    {
        $methodPassword = 'get'.ucfirst($constraint->fieldPassword);
        $methodUser = 'get'.ucfirst($constraint->fieldUser);
        $user = $object->$methodUser();

        $currentPassword = $user->getPassword();
        $encodedPassword = $this->encoder->encodePassword($user, $object->$methodPassword());

        if ($currentPassword !== $encodedPassword) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
