<?php

namespace OAuthBundle\Controller;

use JMS\Serializer\SerializationContext;
use OAuthBundle\Form\Type\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class OAuthController.
 */
class OAuthController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/login", name="login", methods={"GET", "POST"})
     */
    public function loginAction(Request $request)
    {
        $form = $this->get('form.factory')->createNamed('', LoginType::class, null, [
            'action' => $this->generateUrl('login_check'),
        ]);

        $session = $request->getSession();

        $error = null;
        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContextInterface::AUTHENTICATION_ERROR
            );
        } elseif (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        }

        if (null !== $error) {
            $form->addError(new FormError($this->get('translator')->trans($error->getMessage())));
        }

        return $this->render('oAuth/login.html.twig', [
            'username' => $this->get('security.authentication_utils')->getLastUsername(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return Response
     *
     * @Route("/api/user", name="oauth_api_user", methods={"GET"})
     */
    public function getUserAction()
    {
        return new Response($this->get('jms_serializer')->serialize($this->getUser(), 'json', SerializationContext::create()->setGroups(['OAuth'])));
    }
}
