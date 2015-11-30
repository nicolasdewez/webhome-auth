<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ForgottenPassword;
use Ndewez\WebHome\CommonBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ForgottenPasswordController.
 *
 * @Route("/forgotten-passwords")
 * @Security("has_role('ROLE_AUTH_FOPWD')")
 */
class ForgottenPasswordController extends AbstractController
{
    /**
     * @return Response
     *
     * @Route("", name="app_forgotten_password_list", methods="GET")
     * @Security("has_role('ROLE_AUTH_FOPWD_SHOW')")
     */
    public function listAction()
    {
        $elements = $this->get('doctrine.orm.entity_manager')->getRepository('AppBundle:ForgottenPassword')->findBy([], ['id' => 'ASC']);

        return $this->render('forgottenPassword/list.html.twig', ['elements' => $elements]);
    }

    /**
     * @param ForgottenPassword $forgottenPassword
     * @param Request           $request
     *
     * @return JsonResponse
     *
     * @Route("/{id}/delete", requirements={"id": "^\d+$"}, name="app_forgotten_password_delete", methods="DELETE")
     * @Security("has_role('ROLE_AUTH_FOPWD_DEL')")
     */
    public function deleteAction(ForgottenPassword $forgottenPassword, Request $request)
    {
        $this->assertXmlHttpRequest($request);

        $manager = $this->get('doctrine.orm.entity_manager');
        $manager->remove($forgottenPassword);
        $manager->flush();

        return new JsonResponse(['message' => $this->get('translator')->trans('forgotten_passwords.message.delete')]);
    }
}
