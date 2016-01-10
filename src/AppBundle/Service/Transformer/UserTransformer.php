<?php

namespace AppBundle\Service\Transformer;

use AppBundle\Entity\User;
use Ndewez\WebHome\CommonBundle\User\WebHomeUser;

/**
 * Class UserTransformer.
 */
class UserTransformer
{
    /**
     * @param User $user
     *
     * @return WebHomeUser
     */
    public function entityToWebHomeUser(User $user)
    {
        $model = new WebHomeUser(
            $user->getUsername(),
            '',
            $user->getFirstName(),
            $user->getLastName(),
            $user->getLocale(),
            $user->getAccessTokens()->last()->getToken(),
            ''
        );

        return $model;
    }
}
