<?php

namespace AppBundle\Service;

use Ndewez\WebHome\CommonBundle\Menu\GetterAuthorizationsInterface;
use Ndewez\WebHome\CommonBundle\Model\Authorization;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class GetterAuthorizations.
 */
class GetterAuthorizations implements GetterAuthorizationsInterface
{
    /** @var TokenStorage*/
    private $tokenStorage;

    /**
     * @param TokenStorage $tokenStorage
     */
    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        $token = $this->tokenStorage->getToken();
        if (null === $token || !is_object($token->getUser())) {
            return [];
        }

        $authorizations = [];
        $collection = $token->getUser()->getGroup()->getAuthorizations();
        foreach ($collection as $element) {
            $authorization = new Authorization();
            $authorization
                ->setCodeAction($element->getCode())
                ->setGranted(true);

            $authorizations[] = $authorization;
        }

        return $authorizations;
    }
}
