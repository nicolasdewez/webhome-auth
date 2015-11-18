<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Ndewez\WebHome\AuthApiBundle\V0\Model\UserApplication;
use Ndewez\WebHome\AuthApiBundle\V0\Model\UserGranted;

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
        foreach ($user->getGroup()->getAuthorizations() as $authorization) {
            if ($codeAction === $authorization->getCode()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param User   $user
     * @param string $codeApplication
     *
     * @return bool
     */
    public function isApplicationGranted(User $user, $codeApplication)
    {
        foreach ($user->getGroup()->getApplications() as $application) {
            if ($codeApplication === $application->getCode()) {
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

    /**
     * @param User $user
     * @param $codeApplication
     *
     * @return UserApplication
     */
    public function buildUserApplication(User $user, $codeApplication)
    {
        $userApplication = new UserApplication();
        $userApplication->setUsername($user->getUsername())
            ->setCodeApplication($codeApplication)
            ->setGranted($this->isApplicationGranted($user, $codeApplication));

        return $userApplication;
    }
}
