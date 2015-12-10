<?php

namespace AppBundle\Controller\V0;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController.
 *
 * @Route("/users")
 */
class UserController extends Controller
{
    /**
     * @param string  $username
     * @param Request $request
     *
     * @return Response
     *
     * Get user information
     *
     * @ApiDoc(
     *  resource = true,
     *  description = "Get user information"
     * )
     *
     * @Route("/users/{username}", name="api_user_information", methods="GET")
     */
    public function getInformationAction($username, Request $request)
    {
        $user = $this->get('doctrine')->getRepository('AppBundle:User')->findOneBy(['username' => $username]);
        if (null === $user) {
            return new JsonResponse([
                'message' => $this->get('translator')->trans('username.not_found', [], 'validators'),
            ], Response::HTTP_NOT_FOUND);
        }

        return new Response(
            $this->get('serializer')->serialize(
                $this->get('app.transform.user')->entityToModel($user),
                'json'
            ),
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }

    /**
     * @param string $username
     * @param string $codeAction
     *
     * @return Response
     *
     * Check if user is granted
     *
     * @ApiDoc(
     *  resource = true,
     *  description = "Check if user is granted"
     * )
     *
     * @Route("/{username}/granted/{codeAction}", name="api_user_granted", methods="GET")
     */
    public function isGrantedAction($username, $codeAction)
    {
        $user = $this->get('doctrine')->getRepository('AppBundle:User')->findOneBy(['username' => $username]);
        if (null === $user) {
            return new JsonResponse([
                'message' => $this->get('translator')->trans('username.not_found', [], 'validators'),
            ], Response::HTTP_NOT_FOUND);
        }

        $userGranted = $this->get('app.authorization')->buildUserGranted($user, $codeAction);

        return new Response(
            $this->get('serializer')->serialize($userGranted, 'json'),
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }

    /**
     * @param string $username
     * @param string $codeApplication
     *
     * @return Response
     *
     * Check if user is granted for application
     *
     * @ApiDoc(
     *  resource = true,
     *  description = "Check if user is granted for application"
     * )
     *
     * @Route("/{username}/application/{codeApplication}", name="api_user_application_granted", methods="GET")
     */
    public function isApplicationGrantedAction($username, $codeApplication)
    {
        $user = $this->get('doctrine')->getRepository('AppBundle:User')->findOneBy(['username' => $username]);
        if (null === $user) {
            return new JsonResponse([
                'message' => $this->get('translator')->trans('username.not_found', [], 'validators'),
            ], Response::HTTP_NOT_FOUND);
        }

        $userApplication = $this->get('app.authorization')->buildUserApplication($user, $codeApplication);

        return new Response(
            $this->get('serializer')->serialize($userApplication, 'json'),
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }
}
