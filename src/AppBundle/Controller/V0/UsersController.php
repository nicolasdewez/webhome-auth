<?php

namespace AppBundle\Controller\V0;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UsersController.
 * @Route("/users")
 */
class UsersController extends Controller
{
    /**
     * @param string $accessToken
     *
     * @return Response
     *
     * @Route("/access-token/{accessToken}", name="app_v0_users_access_token", methods="GET")
     */
    public function usersAction($accessToken)
    {
        $transformer = $this->get('app.transformer.user');
        $token = $this->get('doctrine.orm.entity_manager')
            ->getRepository('OAuthBundle:AccessToken')
            ->findOneBy(['token' => $accessToken])
        ;

        if (!$token || $token->hasExpired()) {
            return new Response('', Response::HTTP_BAD_REQUEST);
        }

        return new Response(
            $this->get('serializer')->serialize($transformer->entityToWebHomeUser($token->getUser()), 'json'),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }
}
