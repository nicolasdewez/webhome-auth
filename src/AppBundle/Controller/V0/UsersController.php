<?php

namespace AppBundle\Controller\V0;

use OAuthBundle\Entity\AccessToken;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
     * @param AccessToken $token
     *
     * @return Response
     *
     * @Route("/access-token/{accessToken}", name="app_v0_users_access_token", methods="GET")
     * @ParamConverter("token", class="OAuthBundle:AccessToken", options={"mapping": {"accessToken": "token"}})
     */
    public function getByAccessTokenAction(AccessToken $token)
    {
        $transformer = $this->get('app.transformer.user');
        if ($token->hasExpired()) {
            return new Response(sprintf('No token found (%s)', $token), Response::HTTP_NOT_FOUND);
        }

        return new Response(
            $this->get('serializer')->serialize($transformer->entityToWebHomeUser($token->getUser()), 'json'),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }
}
