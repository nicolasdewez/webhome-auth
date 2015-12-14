<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Group;
use AppBundle\Form\Type\GroupType;
use Ndewez\WebHome\CommonBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class GroupsController.
 *
 * @Route("/groups")
 * @Security("has_role('ROLE_AUTH_GRPS')")
 */
class GroupsController extends AbstractController
{
    /**
     * @return Response
     *
     * @Route("", name="app_groups_list", methods="GET")
     * @Security("has_role('ROLE_AUTH_GRPS_SHOW')")
     */
    public function listAction()
    {
        $groups = $this->get('doctrine.orm.entity_manager')->getRepository('AppBundle:Group')->findBy([], ['code' => 'ASC']);

        return $this->render('groups/list.html.twig', ['groups' => $groups]);
    }

    /**
     * @param Group   $group
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/{id}", name="app_groups_edit", requirements={"id": "^\d+$"}, methods={"GET", "POST"})
     * @Security("has_role('ROLE_AUTH_GRPS_EDIT')")
     */
    public function editAction(Group $group, Request $request)
    {
        $form = $this->get('form.factory')->create(GroupType::class, $group, [
            'delete' => $this->isGroupDeletable($group),
            'activate' => $this->isGroupActivate($group),
        ]);

        if ($form->handleRequest($request) && $form->isValid()) {
            $manager = $this->get('doctrine.orm.entity_manager');

            // Delete section
            if ($form->has('delete') && $form->get('delete')->isClicked()) {
                if ($group->hasUser()) {
                    $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('groups.error.contains_user'));

                    return new RedirectResponse($this->generateUrl('app_groups_list'));
                }

                // Delete element and redirect
                $manager->remove($group);
                $manager->flush();
                $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('groups.message.delete'));

                return new RedirectResponse($this->generateUrl('app_groups_list'));
            }

            $manager->flush();
            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('groups.message.edit'));
        }

        return $this->render('groups/edit.html.twig', ['form' => $form->createView(), 'group' => $group]);
    }

    /**
     * @param Group $group
     *
     * @return Response
     *
     * @Route("/show/{id}", name="app_groups_show", requirements={"id": "^\d+$"}, methods="GET")
     * @Security("has_role('ROLE_AUTH_GRPS_SHOW')")
     */
    public function showAction(Group $group)
    {
        $form = $this->get('form.factory')->create(GroupType::class, $group, ['submit' => false]);

        return $this->render('groups/show.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/add", name="app_groups_add", methods={"GET", "POST"})
     * @Security("has_role('ROLE_AUTH_GRPS_ADD')")
     */
    public function addAction(Request $request)
    {
        $group = new Group();
        $form = $this->get('form.factory')->create(GroupType::class, $group);

        if ($form->handleRequest($request) && $form->isValid()) {
            $manager = $this->get('doctrine.orm.entity_manager');
            $manager->persist($group);
            $manager->flush();

            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('groups.message.add'));

            return new RedirectResponse($this->generateUrl('app_groups_edit', ['id' => $group->getId()]));
        }

        return $this->render('groups/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Group   $group
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @Route("/{id}/activate", requirements={"id": "^\d+$"}, name="app_groups_activate", methods="PATCH")
     * @Security("has_role('ROLE_AUTH_GRPS_ACTIV')")
     */
    public function activateAction(Group $group, Request $request)
    {
        $this->assertXmlHttpRequest($request);

        if (!$this->isGroupActivate($group)) {
            throw new BadRequestHttpException($this->get('translator')->trans('groups.error.not_activate'));
        }

        $group->setActive(true);
        $this->get('doctrine.orm.entity_manager')->flush();

        return new JsonResponse(['message' => $this->get('translator')->trans('groups.message.active')]);
    }

    /**
     * @param Group   $group
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @Route("/{id}/deactivate", requirements={"id": "^\d+$"}, name="app_groups_deactivate", methods="PATCH")
     * @Security("has_role('ROLE_AUTH_GRPS_ACTIV')")
     */
    public function deactivateAction(Group $group, Request $request)
    {
        $this->assertXmlHttpRequest($request);

        if (!$this->isGroupActivate($group)) {
            throw new BadRequestHttpException($this->get('translator')->trans('groups.error.not_deactivate'));
        }

        $group->setActive(false);
        $this->get('doctrine.orm.entity_manager')->flush();

        return new JsonResponse(['message' => $this->get('translator')->trans('groups.message.inactive')]);
    }

    /**
     * @param Group   $group
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @Route("/{id}/delete", requirements={"id": "^\d+$"}, name="app_groups_delete", methods="DELETE")
     * @Security("has_role('ROLE_AUTH_GRPS_DEL')")
     */
    public function deleteAction(Group $group, Request $request)
    {
        $this->assertXmlHttpRequest($request);

        if (!$this->isGroupDeletable($group)) {
            throw new BadRequestHttpException($this->get('translator')->trans('groups.error.contains_user'));
        }

        $manager = $this->get('doctrine.orm.entity_manager');
        $manager->remove($group);
        $manager->flush();

        return new JsonResponse(['message' => $this->get('translator')->trans('groups.message.delete')]);
    }

    /**
     * @param Group $group
     *
     * @return bool
     */
    private function isGroupDeletable(Group $group)
    {
        return $this->isGranted('ROLE_AUTH_GRPS_DEL') && !$group->hasUser() && !$group->isSuperAdministrator();
    }

    /**
     * @param Group $group
     *
     * @return bool
     */
    private function isGroupActivate(Group $group)
    {
        return $this->isGranted('ROLE_AUTH_GRPS_ACTIV') && !$group->isSuperAdministrator();
    }
}
