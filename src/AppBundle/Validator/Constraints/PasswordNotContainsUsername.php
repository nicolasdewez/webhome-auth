<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class PasswordNotContainsUsername.
 *
 * @Annotation
 */
class PasswordNotContainsUsername extends Constraint
{
    /** @var string */
    public $message = 'password.not_contains_username';

    /** @var string */
    public $fieldPassword = 'password';

    /**
     * {@inheritDoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
