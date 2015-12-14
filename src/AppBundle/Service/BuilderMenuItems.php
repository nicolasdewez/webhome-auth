<?php

namespace AppBundle\Service;

use Ndewez\WebHome\CommonBundle\Menu\BuilderMenuItemsInterface;
use Ndewez\WebHome\CommonBundle\Model\Authorization as AuthorizationModel;
use Ndewez\WebHome\CommonBundle\Model\MenuItemLink;

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

        $item = new MenuItemLink();
        $item
            ->setTitle('menu.home')
            ->setRoute('app_home');

        $items[] = $item;

        if ($this->isGranted('AUTH_GRPS', $authorizations)) {
            $item = new MenuItemLink();
            $item
                ->setTitle('menu.groups')
                ->setRoute('app_groups_list');

            $items[] = $item;
        }

        if ($this->isGranted('AUTH_USERS', $authorizations)) {
            $item = new MenuItemLink();
            $item
                ->setTitle('menu.users')
                ->setRoute('app_users_list');

            $items[] = $item;
        }

        if ($this->isGranted('AUTH_FOPWD', $authorizations)) {
            $item = new MenuItemLink();
            $item
                ->setTitle('menu.forgotten_password')
                ->setRoute('app_forgotten_password_list');

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
