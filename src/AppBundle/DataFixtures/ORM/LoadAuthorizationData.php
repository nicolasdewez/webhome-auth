<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Application;
use AppBundle\Entity\Authorization;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadAuthorizationData.
 */
class LoadAuthorizationData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
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
        $authorizations = [
            ['codeApp' => 'AUTH', 'code' => 'AUTH_USER_ALL', 'title' => 'authorization.auth.user_all'],
        ];

        foreach ($authorizations as $authoriz) {
            /** @var Application $application */
            $application = $this->getReference('app_'.$authoriz['codeApp']);
            $authorization = new Authorization();
            $authorization->setCode($authoriz['code']);
            $authorization->setTitle($authoriz['title']);
            $authorization->setApplication($application);

            $application->getAuthorizations()->add($authorization);

            $this->addReference('auth_'.$authoriz['code'], $authorization);
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 4;
    }
}
