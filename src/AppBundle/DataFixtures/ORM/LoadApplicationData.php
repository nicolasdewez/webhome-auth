<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Application;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadApplicationData.
 */
class LoadApplicationData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
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
            ['code' => 'APP', 'title' => 'WebHome-App'],
            ['code' => 'AUTH', 'title' => 'WebHome-Auth'],
        ];

        foreach ($fixtures as $fixture) {
            $application = new Application();
            $application->setCode($fixture['code']);
            $application->setTitle($fixture['title']);

            $manager->persist($application);
            $manager->flush();

            $this->addReference('app_'.$fixture['code'], $application);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3;
    }
}
