<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class CheckCurrentPassword.
 *
 * @Annotation
 */
class CheckCurrentPassword extends Constraint
{
    /** @var string */
    public $message = 'password.current';

    /** @var string */
    public $fieldPassword = 'password';

    /** @var string */
    public $fieldUser = 'user';

    /**
     * {@inheritDoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    /**
     * {@inheritDoc}
     */
    public function validatedBy()
    {
        return 'app_check_current_password';
    }
}
