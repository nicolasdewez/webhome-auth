<?php

namespace AppBundle\Service\Transformer;

use AppBundle\Entity\User;
use Ndewez\WebHome\AuthApiBundle\V0\Model\Application;
use Ndewez\WebHome\AuthApiBundle\V0\Model\User as UserModel;

/**
 * Class UserTransformer.
 */
class UserTransformer
{
    /** @var AuthorizationTransformer */
    private $authorizationTransformer;

    /**
     * @param AuthorizationTransformer $authorizationTransformer
     */
    public function __construct(AuthorizationTransformer $authorizationTransformer)
    {
        $this->authorizationTransformer = $authorizationTransformer;
    }

    /**
     * @param User $user
     *
     * @return UserModel
     */
    public function entityToModel(User $user)
    {
        $userModel = new UserModel();
        $userModel->setUsername($user->getUsername())
            ->setGroup($user->getGroup()->getCode())
            ->setFirstName($user->getFirstName())
            ->setLastName($user->getLastName())
            ->setBirthDate($user->getBirthDate())
            ->setEmail($user->getEmail())
            ->setActive($user->isActive());

        foreach ($user->getGroup()->getApplications() as $application) {
            $applicationModel = new Application();
            $applicationModel
                ->setCodeApplication($application->getCode())
                ->setGranted(true);

            $userModel->addApplication($applicationModel);
        }

        foreach ($user->getGroup()->getAuthorizations() as $authorization) {
            $userModel->addAuthorization($this->authorizationTransformer->entityToModel($authorization));
        }

        return $userModel;
    }
}
