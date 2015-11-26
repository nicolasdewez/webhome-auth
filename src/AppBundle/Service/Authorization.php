<?php

namespace AppBundle\Service;

use AppBundle\Entity\Group;
use AppBundle\Entity\User;
use AppBundle\Model\AuthorizationGranted;
use AppBundle\Model\AuthorizationGroup;
use Doctrine\ORM\EntityManagerInterface;
use Ndewez\WebHome\AuthApiBundle\V0\Model\UserApplication;
use Ndewez\WebHome\AuthApiBundle\V0\Model\UserGranted;

/**
 * Class Authorization.
 */
class Authorization
{
    /** @var EntityManagerInterface */
    private $manager;

    /**
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

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

    /**
     * @param Group $group
     *
     * @return AuthorizationGroup
     */
    public function buildAuthorizationGroup(Group $group)
    {
        $authorizationGroup = new AuthorizationGroup();

        $authorizations = $this->manager->getRepository('AppBundle:Authorization')->findBy([], ['id' => 'ASC']);
        foreach ($authorizations as $authorization) {
            $AuthorizationGranted = new AuthorizationGranted($authorization);
            $AuthorizationGranted->setGranted($group->getAuthorizations()->contains($authorization));

            $authorizationGroup->getAuthorizations()->add($AuthorizationGranted);
        }

        return $authorizationGroup;
    }

    /**
     * @param Group              $group
     * @param AuthorizationGroup $authorizationGroup
     */
    public function saveAuthorizationGroup(Group $group, AuthorizationGroup $authorizationGroup)
    {
        $authorizations = $group->getAuthorizations();

        foreach ($authorizationGroup->getAuthorizations() as $authorizationGranted) {
            $authorization = $authorizationGranted->getAuthorization();
            if ($authorizationGranted->isGranted() && !$authorizations->contains($authorization)) {
                $authorizations->add($authorization);
                continue;
            }

            if (!$authorizationGranted->isGranted() && $authorizations->contains($authorization)) {
                $authorizations->removeElement($authorization);
            }
        }

        $this->manager->flush();
    }
}
