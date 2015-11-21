<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Role.
 *
 * @ORM\Table(name="groups")
 * @ORM\Entity
 */
class Group
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(length=20)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column()
     */
    private $title;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Authorization", inversedBy="groups", cascade={"persist"})
     */
    private $authorizations;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="User", mappedBy="group")
     */
    private $users;

    public function __construct()
    {
        $this->active = true;
        $this->authorizations = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set code.
     *
     * @param string $code
     *
     * @return Group
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Group
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return Group
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @return ArrayCollection
     */
    public function getAuthorizations()
    {
        return $this->authorizations;
    }

    /**
     * @return array
     */
    public function getAuthorizationsCodes()
    {
        $codes = $this->authorizations->getValues();
        array_walk($codes, function(&$item) {
            $item = Authorization::PREFIX.$item->getCode();
        });

        return $codes;
    }

    /**
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @return ArrayCollection
     */
    public function getApplications()
    {
        $applications = new ArrayCollection();
        foreach ($this->authorizations as $authorization) {
            if (!$applications->contains($authorization->getApplication())) {
                $applications->add($authorization->getApplication());
            }
        }

        return $applications;
    }

    /**
     * @return bool
     */
    public function hasUser()
    {
        return count($this->users) > 0;
    }
}
