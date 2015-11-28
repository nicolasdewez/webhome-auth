<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ForgottenPassword;
use AppBundle\Model\ChangePassword;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomeController.
 */
class HomeController extends Controller
{
    /**
     * @return Response
     *
     * @Route("/", name="app_home", methods="GET")
     * @Route("/home", name="app_home_complete", methods="GET")
     */
    public function indexAction()
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
        $changePassword = new ChangePassword($this->getUser());
        $form = $this->createForm('app_change_password', $changePassword);
        if ($form->handleRequest($request) && $form->isValid()) {
            $this->get('app.password')->changePassword($this->getUser(), $changePassword->getPassword());
            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('change_password.message.changed'));

            return new RedirectResponse($this->generateUrl('app_home_show_my_account'));
        }

        return $this->render('home/changePassword.html.twig', ['form' => $form->createView()]);
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
        $forgottenPassword = new ForgottenPassword();
        $form = $this->createForm('app_forgotten_password', $forgottenPassword);

        if ($form->handleRequest($request) && $form->isValid()) {
            $manager = $this->get('doctrine.orm.entity_manager');
            $manager->persist($forgottenPassword);
            $manager->flush();

            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('home.message.forgotten_password.add'));

            return new RedirectResponse($this->generateUrl('login'));
        }

        return $this->render('home/forgottenPassword.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     *
     * @return array
     *
     * @Route("/show-account", name="app_home_show_my_account", methods={"GET", "POST"})
     */
    public function showMyAccountAction(Request $request)
    {
        $form = $this->createForm('app_account', $this->getUser(), ['validation_groups' => 'Account']);
        if ($form->handleRequest($request) && $form->isValid()) {
            $this->get('doctrine.orm.entity_manager')->flush();
            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('home.message.show_account.edit'));

            return new RedirectResponse($this->generateUrl('app_home_show_my_account'));
        }

        return $this->render('home/showMyAccount.html.twig', ['form' => $form->createView()]);
    }
}
