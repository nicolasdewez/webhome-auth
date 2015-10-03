<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadAuthorizationData.
 */
class LoadAuthorizationsData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
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
        $adminAuthorizations = $manager->getRepository('AppBundle:Authorization')->getForAdmin();
        $userAuthorizations = $manager->getRepository('AppBundle:Authorization')->getForUser();

        $users = ['ndewez', 'user', 'user2'];
        foreach ($users as $user) {
            /* @var User */
            $user = $this->getReference($user);

            $authorizations = $userAuthorizations;
            if (User::ROLE_ADMIN === $user->getRole()) {
                $authorizations = $adminAuthorizations;
            }

            foreach ($authorizations as $authorization) {
                $user->getAuthorizations()->add($authorization);
            }
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }
}
