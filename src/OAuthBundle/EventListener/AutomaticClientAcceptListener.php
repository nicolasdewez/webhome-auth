<?php

namespace OAuthBundle\EventListener;

use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\OAuthServerBundle\Event\OAuthEvent;

/**
 * Class AutomaticClientAcceptListener.
 * Automatically accept the client application, without requiring confirmation to the user.
 */
class AutomaticClientAcceptListener
{
    /** @var ObjectManager */
    private $manager;

    /**
     * @param ObjectManager $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param OAuthEvent $event
     */
    public function onPreAuthorizationProcess(OAuthEvent $event)
    {
        /** @var User $user */
        $user = $event->getUser();
        if ($user->getClients()->contains($event->getClient())) {
            $event->setAuthorizedClient(true);

            return;
        }

        $user->getClients()->add($event->getClient());
        $this->manager->flush();

        $event->setAuthorizedClient(true);
    }
}
