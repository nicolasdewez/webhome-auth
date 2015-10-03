<?php

namespace AppBundle\Exception;

/**
 * Class PasswordException.
 */
class PasswordException extends \Exception
{
    /** @var array */
    private $errors;

    public function __construct($errors = array())
    {
        parent::__construct('Error(s) in password validation');
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
