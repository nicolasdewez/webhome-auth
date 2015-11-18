<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Group;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadUserData.
 */
class LoadUserData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $fixtures = [
            ['username' => 'ndewez', 'firstName' => 'Nicolas', 'lastName' => 'Dewez', 'locale' => 'en', 'codeGroup' => 'GROUP_ADMIN'],
            ['username' => 'user', 'firstName' => 'Nicolas', 'lastName' => 'Dewez', 'locale' => 'fr', 'codeGroup' => 'GROUP_USER'],
            ['username' => 'user2', 'firstName' => 'Nicolas', 'lastName' => 'Dewez', 'locale' => 'fr', 'codeGroup' => 'GROUP_USER', 'active' => false],
        ];

        foreach ($fixtures as $fixture) {
            /** @var Group $group */
            $group = $this->getReference('group_'.$fixture['codeGroup']);

            $user = new User();
            $user->setUsername($fixture['username']);
            $user->setFirstName($fixture['firstName']);
            $user->setLastName($fixture['lastName']);
            $user->setLocale($fixture['locale']);
            $user->setGroup($group);

            if (isset($fixture['active'])) {
                $user->setActive($fixture['active']);
            }

            $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
            $user->setPassword($encoder->encodePassword($fixture['username'], $user->getSalt()));

            $manager->persist($user);
            $manager->flush();

            $this->addReference('user_'.$fixture['username'], $user);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }
}
