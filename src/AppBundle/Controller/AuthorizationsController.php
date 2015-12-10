<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Group;
use AppBundle\Form\Type\AuthorizationGrantedCollectionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AuthorizationsController.
 *
 * @Route("/groups/{groupId}/authorizations", requirements={"groupId": "^\d+$"})
 * @ParamConverter("group", class="AppBundle:Group", options={"id" = "groupId"})
 *
 * @Security("has_role('ROLE_AUTH_GRPS_AUTHZ')")
 */
class AuthorizationsController extends Controller
{
    /**
     * @param Request $request
     * @param Group   $group
     *
     * @return Response
     *
     * @Route("", name="app_authorizations_list", methods={"GET", "POST"})
     * @Security("has_role('ROLE_AUTH_GRPS_AUTHZ_SHOW')")
     */
    public function listAction(Request $request, Group $group)
    {
        $authorizationService = $this->get('app.authorization');
        $authorizationGroup = $authorizationService->buildAuthorizationGroup($group);
        $form = $this->createForm(AuthorizationGrantedCollectionType::class, $authorizationGroup, ['disabled' => !$this->isGroupEditable($group)]);
        if ($form->handleRequest($request) && $form->isValid()) {
            $authorizationService->saveAuthorizationGroup($group, $authorizationGroup);

            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('authorizations.message.edit'));

            return new RedirectResponse($this->generateUrl('app_authorizations_list', ['groupId' => $group->getId()]));
        }

        return $this->render('authorizations/list.html.twig', ['group' => $group, 'form' => $form->createView()]);
    }

    /**
     * @param Group $group
     *
     * @return bool
     */
    private function isGroupEditable(Group $group)
    {
        return $this->isGranted('ROLE_AUTH_GRPS_AUTHZ_EDIT') && !$group->isSuperAdministrator();
    }
}
