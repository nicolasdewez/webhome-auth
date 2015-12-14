<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

/**
 * Authorization.
 *
 * @ORM\Table(name="authorizations")
 * @ORM\Entity(repositoryClass="AuthorizationRepository")
 */
class Authorization
{
    const PREFIX = 'ROLE_';

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
     * @ORM\Column()
     *
     * @Groups("OAuth")
     */
    private $code;

    /**
     * @var Application
     *
     * @ORM\ManyToOne(targetEntity="Application", inversedBy="authorizations", cascade={"persist"})
     *
     * @Groups("OAuth")
     */
    private $application;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Group", mappedBy="authorizations")
     */
    private $groups;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
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
     * @return Authorization
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
     * Get application.
     *
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Set application.
     *
     * @param Application $application
     *
     * @return Authorization
     */
    public function setApplication(Application $application)
    {
        $this->application = $application;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getGroups()
    {
        return $this->groups;
    }
}
