<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class PasswordNotContainsUsernameValidator.
 */
class PasswordNotContainsUsernameValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($object, Constraint $constraint)
    {
        $methodPassword = 'get'.ucfirst($constraint->fieldPassword);

        if (false !== stripos($object->$methodPassword(), $object->getUsername())) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
