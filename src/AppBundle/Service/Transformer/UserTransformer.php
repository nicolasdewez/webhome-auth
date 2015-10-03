<?php

namespace AppBundle\Service\Transformer;

use AppBundle\Entity\User;
use Ndewez\WebHome\UserApiBundle\V0\Model\Authorization;
use Ndewez\WebHome\UserApiBundle\V0\Model\User as UserModel;

/**
 * Class UserTransformer.
 */
class UserTransformer
{
    /**
     * @param User $user
     *
     * @return UserModel
     */
    public function entityToModel(User $user)
    {
        $userModel = new UserModel();
        $userModel->setUsername($user->getUsername())
            ->setRole($user->getRole())
            ->setFirstName($user->getFirstName())
            ->setLastName($user->getLastName())
            ->setBirthDate($user->getBirthDate())
            ->setEmail($user->getEmail())
            ->setActive($user->isActive());

        foreach ($user->getAuthorizations() as $authorization) {
            $authorizationModel = new Authorization();
            $authorizationModel->setCodeAction($authorization->getCode())
                ->setGranted(true);

            $userModel->addAuthorization($authorizationModel);
        }

        return $userModel;
    }
}
