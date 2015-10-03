<?php

namespace AppBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Password.
 */
class Password
{
    /** @var string */
    private $current;

    /** @var string */
    private $currentSaved;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="password.not_blank")
     * @Assert\Length(min=6, minMessage="password.length")
     */
    private $value;

    /** @var string */
    private $repeat;

    /**
     * @return string
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * @param string $current
     *
     * @return Password
     */
    public function setCurrent($current)
    {
        $this->current = $current;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrentSaved()
    {
        return $this->currentSaved;
    }

    /**
     * @param string $currentSaved
     *
     * @return Password
     */
    public function setCurrentSaved($currentSaved)
    {
        $this->currentSaved = $currentSaved;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return Password
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getRepeat()
    {
        return $this->repeat;
    }

    /**
     * @param string $repeat
     *
     * @return Password
     */
    public function setRepeat($repeat)
    {
        $this->repeat = $repeat;

        return $this;
    }

    /**
     * @Assert\True(message = "password.current.invalid")
     */
    public function isCurrentValid()
    {
        return $this->currentSaved === $this->current;
    }

    /**
     * @Assert\True(message = "password.new.invalid")
     */
    public function isNewValid()
    {
        return $this->value === $this->repeat;
    }
}
