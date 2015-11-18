<?php

namespace AppBundle\Service\Transformer;

use AppBundle\Entity\Authorization;
use Ndewez\WebHome\AuthApiBundle\V0\Model\Authorization as AuthorizationModel;

/**
 * Class AuthorizationTransformer.
 */
class AuthorizationTransformer
{
    /**
     * @param Authorization $authorization
     *
     * @return AuthorizationModel
     */
    public function entityToModel(Authorization $authorization)
    {
        $authorizationModel = new AuthorizationModel();
        $authorizationModel
            ->setCodeAction($authorization->getCode())
            ->setGranted(true);

        return $authorizationModel;
    }
}
