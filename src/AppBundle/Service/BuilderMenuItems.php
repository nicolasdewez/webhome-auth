<?php

namespace AppBundle\Service;

use Ndewez\WebHome\CommonBundle\Menu\BuilderMenuItemsInterface;
use Ndewez\WebHome\AuthApiBundle\V0\Model\Authorization as AuthorizationModel;
use Ndewez\WebHome\CommonBundle\Model\MenuItem;

/**
 * Class BuilderMenu.
 */
class BuilderMenuItems implements BuilderMenuItemsInterface
{
    /**
     * {@inheritDoc}
     */
    public function build(array $authorizations)
    {
        if (0 === count($authorizations)) {
            return [];
        }

        $item = new MenuItem();
        $item
            ->setTitle('menu.auth.home')
            ->setRoute('app_home');

        $items[] = $item;

        if ($this->isGranted('AUTH_GRPS', $authorizations)) {
            $item = new MenuItem();
            $item
                ->setTitle('menu.auth.groups')
                ->setRoute('app_groups_list');

            $items[] = $item;
        }

        if ($this->isGranted('AUTH_USERS', $authorizations)) {
            $item = new MenuItem();
            $item
                ->setTitle('menu.auth.users')
                ->setRoute('app_users_list');

            $items[] = $item;
        }

        return $items;
    }

    /**
     * @param string               $code
     * @param AuthorizationModel[] $authorizations
     *
     * @return bool
     */
    private function isGranted($code, array $authorizations)
    {
        foreach ($authorizations as $authorization) {
            if ($code === $authorization->getCodeAction()) {
                return $authorization->isGranted();
            }
        }

        return false;
    }
}
