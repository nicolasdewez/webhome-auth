<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Authorization;
use AppBundle\Entity\Group;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadAuthorizationUserData.
 */
class LoadAuthorizationUserData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
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
        $authUsers = [
            ['codeGroup' => 'GROUP_ADMIN', 'codeAuth' => 'AUTH_USER_ALL'],
        ];

        foreach ($authUsers as $authUser) {
            /** @var Group $group */
            $group = $this->getReference('group_'.$authUser['codeGroup']);

            /** @var Authorization $authorization */
            $authorization = $this->getReference('auth_'.$authUser['codeAuth']);

            $group->getAuthorizations()->add($authorization);
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 5;
    }
}
