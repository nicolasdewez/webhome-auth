<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
     * @Route("", name="users_list")
     * @Method("GET")
     * @Template()
     */
    public function listAction(Request $request)
    {
        return [];
    }
}
