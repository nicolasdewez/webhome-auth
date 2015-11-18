<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Group;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class UserController.
 *
 * @Route("/groups")
 */
class GroupController extends AbstractController
{
    /**
     * @return array
     *
     * @Route("", name="app_groups_list", methods={"GET"})
     */
    public function listAction()
    {
        $groups = $this->get('doctrine.orm.entity_manager')->getRepository('AppBundle:Group')->findAll();

        return $this->render('group/list.html.twig', ['groups' => $groups]);
    }

    /**
     * @param Group   $group
     * @param Request $request
     *
     * @return array|Response
     *
     * @Route("/{id}", name="app_groups_edit", requirements={"id": "^\d$"}, methods={"GET", "POST"})
     */
    public function editAction(Group $group, Request $request)
    {
        $form = $this->get('form.factory')->create('group', $group, ['delete' => !$group->hasUser()]);

        if ($form->handleRequest($request) && $form->isValid()) {
            $manager = $this->get('doctrine.orm.entity_manager');

            // Delete section
            if ($form->has('delete') && $form->get('delete')->isClicked()) {
                if ($group->hasUser()) {
                    $this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('groups.error.contains_user'));

                    return new RedirectResponse($this->generateUrl('groups_list'));
                }

                // Delete element and redirect
                $manager->remove($group);
                $manager->flush();
                $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('groups.message.delete'));

                return new RedirectResponse($this->generateUrl('groups_list'));
            }

            $manager->flush();
            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('groups.message.edit'));
        }

        return $this->render('group/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Group $group
     *
     * @return Response
     *
     * @Route("/show/{id}", name="app_groups_show", methods="GET")
     */
    public function showAction(Group $group)
    {
        return $this->render('group/show.html.twig', ['group' => $group]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/add", name="app_groups_add", methods={"GET", "POST"})
     */
    public function addAction(Request $request)
    {
        $group = new Group();
        $form = $this->get('form.factory')->create('group', $group);

        if ($form->handleRequest($request) && $form->isValid()) {
            $manager = $this->get('doctrine.orm.entity_manager');
            $manager->persist($group);
            $manager->flush();

            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('groups.message.add'));

            return new RedirectResponse($this->generateUrl('groups_edit', ['id' => $group->getId()]));
        }

        return $this->render('group/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Group   $group
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @Route("/{id}/activate", name="app_groups_activate", methods={"PATCH"})
     */
    public function activateAction(Group $group, Request $request)
    {
        $this->assertXmlHttpRequest($request);

        $group->setActive(true);
        $this->get('doctrine.orm.entity_manager')->flush();

        return new JsonResponse(['state' => true]);
    }

    /**
     * @param Group   $group
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @Route("/{id}/deactivate", name="app_groups_deactivate", methods={"PATCH"})
     */
    public function deactivateAction(Group $group, Request $request)
    {
        $this->assertXmlHttpRequest($request);

        $group->setActive(false);
        $this->get('doctrine.orm.entity_manager')->flush();

        return new JsonResponse(['state' => true]);
    }

    /**
     * @param Group   $group
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @Route("/{id}/delete", name="app_groups_delete", methods={"DELETE"})
     */
    public function deleteAction(Group $group, Request $request)
    {
        $this->assertXmlHttpRequest($request);

        if ($group->hasUser()) {
            throw new BadRequestHttpException($this->get('translator')->trans('groups.error.contains_user'));
        }

        $this->get('doctrine.orm.entity_manager')->remove($group);

        return new JsonResponse(['state' => true]);
    }
}
