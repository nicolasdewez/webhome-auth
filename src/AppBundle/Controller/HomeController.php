<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomeController.
 */
class HomeController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/", name="app_home", methods={"GET"})
     * @Route("/home", name="app_home_complete", methods={"GET"})
     */
    public function indexAction(Request $request)
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @param Request $request
     *
     * @return array
     *
     * @Route("/change-password", name="app_home_change_password", methods={"GET", "POST"})
     */
    public function changePasswordAction(Request $request)
    {
        return $this->render('home/changePassword.html.twig');
    }

    /**
     * @param Request $request
     *
     * @return array
     *
     * @Route("/forgotten-password", name="app_home_forgotten_password", methods={"GET", "POST"})
     */
    public function forgottenPasswordAction(Request $request)
    {
        return $this->render('home/forgottenPassword.html.twig');
    }

    /**
     * @return array
     *
     * @Route("/show-account", name="app_home_show_my_account", methods={"GET"})
     */
    public function showMyAccountAction()
    {
        return $this->render('home/showMyAccount.html.twig');
    }
}
