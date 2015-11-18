<?php

namespace AppBundle\Service;

use AppBundle\Service\Transformer\AuthorizationTransformer;
use Ndewez\WebHome\CommonBundle\Menu\GetterAuthorizationsInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class GetterAuthorizations.
 */
class GetterAuthorizations implements GetterAuthorizationsInterface
{
    /** @var TokenStorage*/
    private $tokenStorage;

    /** @var AuthorizationTransformer */
    private $authorizationTransformer;

    /**
     * @param TokenStorage             $tokenStorage
     * @param AuthorizationTransformer $authorizationTransformer
     */
    public function __construct(TokenStorage $tokenStorage, AuthorizationTransformer $authorizationTransformer)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authorizationTransformer = $authorizationTransformer;
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
            $authorizations[] = $this->authorizationTransformer->entityToModel($element);
        }

        return $authorizations;
    }
}
