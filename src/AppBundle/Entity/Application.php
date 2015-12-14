<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

/**
 * Application.
 *
 * @ORM\Table(name="applications")
 * @ORM\Entity
 */
class Application
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
     * @ORM\Column(length=5)
     *
     * @Groups("OAuth")
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column()
     *
     * @Groups("OAuth")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column()
     *
     * @Groups("OAuth")
     */
    private $href;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Authorization", mappedBy="application", cascade={"persist"})
     */
    private $authorizations;

    public function __construct()
    {
        $this->authorizations = new ArrayCollection();
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
     * @return Application
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
     * @return Application
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
     * Set href.
     *
     * @param string $href
     *
     * @return Application
     */
    public function setHref($href)
    {
        $this->href = $href;

        return $this;
    }

    /**
     * Get href.
     *
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * @return ArrayCollection
     */
    public function getAuthorizations()
    {
        return $this->authorizations;
    }
}
