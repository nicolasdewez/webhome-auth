<?php

namespace AppBundle\Entity;

use AppBundle\Validator\Constraints as AppAssert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Ndewez\WebHome\CommonBundle\Model\Application;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User.
 *
 * @ORM\Table(name="users", uniqueConstraints={
 *      @ORM\UniqueConstraint(name="users_username_unique", columns = {"username"})
 * }))
 * @ORM\Entity
 * @UniqueEntity("username", groups={"Add", "Default"})
 * @AppAssert\PasswordNotContainsUsername
 */
class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(length=30)
     *
     * @Assert\NotBlank(groups={"Add", "Default"})
     * @Assert\Length(min=3, max=30, groups={"Add", "Default"})
     * @Assert\Regex("/^[A-Z_0-9]+$/i", groups={"Add", "Default"})
     *
     * @Groups("OAuth")
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column
     *
     * @Assert\NotBlank(groups="Add")
     * @Assert\Length(min=6, max=255, groups="Add")
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column
     */
    private $salt;

    /**
     * @var Group
     *
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="users")
     *
     * @Assert\NotBlank(groups={"Add", "Default"})
     *
     * @Groups("OAuth")
     */
    private $group;

    /**
     * @var string
     *
     * @ORM\Column
     *
     * @Assert\NotBlank(groups={"Add", "Account", "Default"})
     * @Assert\Length(max=255)
     *
     * @Groups("OAuth")
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column
     *
     * @Assert\NotBlank(groups={"Add", "Account", "Default"})
     * @Assert\Length(max=255, groups={"Add", "Account", "Default"})
     *
     * @Groups("OAuth")
     */
    private $lastName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     *
     * @Assert\Date(groups={"Add", "Account", "Default"})
     */
    private $birthDate;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     *
     * @Assert\Email(groups={"Add", "Account", "Default"})
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(length=3)
     *
     * @Assert\Choice(choices={"fr", "en"}, groups={"Add", "Account", "Default"})
     *
     * @Groups("OAuth")
     */
    private $locale;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="OAuthBundle\Entity\Client")
     */
    private $clients;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $active;

    public function __construct()
    {
        $this->salt = $this->initSalt();
        $this->active = true;
        $this->clients = new ArrayCollection();
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
     * Set username.
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt.
     *
     * @param string $salt
     *
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Init salt.
     *
     * @return User
     */
    public function initSalt()
    {
        $this->salt = md5(uniqid());

        return $this;
    }

    /**
     * Get salt.
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set group.
     *
     * @param Group $group
     *
     * @return User
     */
    public function setGroup(Group $group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group.
     *
     * @return Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Set firstName.
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName.
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set birthDate.
     *
     * @param \DateTime $birthDate
     *
     * @return User
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate.
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set locale.
     *
     * @param string $locale
     *
     * @return User
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return User
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active.
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
    public function getClients()
    {
        return $this->clients;
    }

    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        return serialize(
            [
                $this->id,
                $this->username,
            ]
        );
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize($serialized)
    {
        list($this->id, $this->username) = unserialize($serialized);
    }

    /**
     * {@inheritDoc}
     */
    public function getRoles()
    {
        return $this->group->getAuthorizationsCodes();
    }

    /**
     * @return array
     */
    public function getApplications()
    {
        $applications = [];
        foreach ($this->getGroup()->getApplications() as $application) {
            $applications[] = new Application(
                $application->getCode(),
                $application->getTitle(),
                $application->getHref()
            );
        }

        return $applications;
    }

    /**
     * {@inheritDoc}
     */
    public function eraseCredentials()
    {
    }

    /**
     * {@inheritDoc}
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function isEnabled()
    {
        return $this->active && $this->group->isActive();
    }

    /**
     * @return bool
     */
    public function isSuperAdministrator()
    {
        return $this->group->isSuperAdministrator();
    }
}
