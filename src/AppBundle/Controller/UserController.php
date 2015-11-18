<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class UserController.
 *
 * @Route("/users")
 */
class UserController extends Controller
{
    /**
     * @param Request $request
     *
     * @return array
     *
     * @Route("", name="app_users_list", methods={"GET"})
     */
    public function listAction(Request $request)
    {
        return $this->render('user/list.html.twig');
    }
}
