<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Ndewez\WebHome\UserApiBundle\V0\Model\UserGranted;

/**
 * Class Authorization.
 */
class Authorization
{
    /**
     * @param User   $user
     * @param string $codeAction
     *
     * @return bool
     */
    public function isGranted(User $user, $codeAction)
    {
        foreach ($user->getAuthorizations() as $authorization) {
            if ($codeAction === $authorization->getCode()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param User $user
     * @param $codeAction
     *
     * @return UserGranted
     */
    public function buildUserGranted(User $user, $codeAction)
    {
        $userGranted = new UserGranted();
        $userGranted->setUsername($user->getUsername())
            ->setCodeAction($codeAction)
            ->setGranted($this->isGranted($user, $codeAction));

        return $userGranted;
    }
}
