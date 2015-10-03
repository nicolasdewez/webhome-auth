<?php

namespace AppBundle\Controller\V0;

use AppBundle\Exception\PasswordException;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Patch;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController.
 */
class UserController extends Controller
{
    /**
     * @param string $username
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
     * @Get("/users/{username}", name="api_user_information")
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
     * @Get("/users/{username}/granted/{codeAction}", name="api_user_granted")
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
     * @param string  $username
     * @param Request $request
     *
     * @return Response
     *
     * Change password for user
     *
     * @ApiDoc(
     *  resource = true,
     *  description = "Change password for user"
     * )
     *
     * @Patch("/users/{username}", name="api_change_password")
     */
    public function changePasswordAction($username, Request $request)
    {
        $changePassword = $this->get('serializer')->deserialize($request->getContent(), 'Ndewez\WebHome\UserApiBundle\V0\Model\ChangePassword', 'json');
        $user = $this->get('doctrine')->getRepository('AppBundle:User')->findOneBy(['username' => $username]);

        if (null === $user || $changePassword->getUsername() !== $user->getUsername()) {
            return new JsonResponse([
                'message' => $this->get('translator')->trans('username.not_found', [], 'validators'),
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $this->get('app.password')->change($user, $changePassword);
        } catch (PasswordException $exception) {
            return new JsonResponse($exception->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse([
            'changed' => true,
        ]);
    }
}
