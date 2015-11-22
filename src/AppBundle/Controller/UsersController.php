<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Group;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UsersController.
 *
 * @Route("/users")
 * @Security("has_role('ROLE_AUTH_USERS')")
 */
class UsersController extends AbstractController
{
    /**
     * @return Response
     *
     * @Route("", name="app_users_list", methods="GET")
     * @Security("has_role('ROLE_AUTH_USERS_SHOW')")
     */
    public function listAction()
    {
        $users = $this->get('doctrine.orm.entity_manager')->getRepository('AppBundle:User')->findBy([], ['username' => 'ASC']);

        return $this->render('users/list.html.twig', ['users' => $users]);
    }

    /**
     * @param Group $group
     *
     * @return Response
     *
     * @Route("/group/{id}", name="app_users_list_group", methods="GET")
     * @Security("has_role('ROLE_AUTH_USERS_SHOW')")
     */
    public function listByGroupAction(Group $group)
    {
        $users = $this->get('doctrine.orm.entity_manager')->getRepository('AppBundle:User')->findBy(['group'=> $group], ['username' => 'ASC']);

        return $this->render('users/listByGroup.html.twig', ['users' => $users, 'group' => $group]);
    }

    /**
     * @param User    $user
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/{id}", name="app_users_edit", requirements={"id": "^\d+$"}, methods={"GET", "POST"})
     * @Security("has_role('ROLE_AUTH_USERS_EDIT')")
     */
    public function editAction(User $user, Request $request)
    {
        $originalPassword = $user->getPassword();
        $form = $this->get('form.factory')->create('app_user', $user, ['delete' => true]);

        if ($form->handleRequest($request) && $form->isValid()) {
            $manager = $this->get('doctrine.orm.entity_manager');

            // Delete section
            if ($form->has('delete') && $form->get('delete')->isClicked()) {
                $manager->remove($user);
                $manager->flush();
                $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('users.message.delete'));

                return new RedirectResponse($this->generateUrl('app_users_list'));
            }

            $this->get('app.password')->encodePassword($user, $originalPassword);

            $manager->flush();
            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('users.message.edit'));
        }

        return $this->render('users/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param User $user
     *
     * @return Response
     *
     * @Route("/show/{id}", name="app_users_show", requirements={"id": "^\d+$"}, methods="GET")
     * @Security("has_role('ROLE_AUTH_USERS_SHOW')")
     */
    public function showAction(User $user)
    {
        $form = $this->get('form.factory')->create('app_user', $user, ['submit' => false]);

        return $this->render('users/show.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param Group   $group
     *
     * @return Response
     *
     * @Route("/add", name="app_users_add", methods={"GET", "POST"})
     * @Route("/add/{id}", name="app_users_add_by_group", methods={"GET", "POST"})
     * @Security("has_role('ROLE_AUTH_USERS_ADD')")
     */
    public function addAction(Request $request, Group $group = null)
    {
        $user = new User();
        if (null !== $group) {
            $user->setGroup($group);
        }

        $form = $this->get('form.factory')->create('app_user', $user, ['validation_groups' => 'Add']);

        if ($form->handleRequest($request) && $form->isValid()) {
            $this->get('app.password')->encodePassword($user);

            $manager = $this->get('doctrine.orm.entity_manager');
            $manager->persist($user);
            $manager->flush();

            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('users.message.add'));

            return new RedirectResponse($this->generateUrl('app_users_edit', ['id' => $user->getId()]));
        }

        return $this->render('users/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param User    $user
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @Route("/{id}/activate", requirements={"id": "^\d+$"}, name="app_users_activate", methods="PATCH")
     * @Security("has_role('ROLE_AUTH_USERS_ACTIV')")
     */
    public function activateAction(User $user, Request $request)
    {
        $this->assertXmlHttpRequest($request);

        $user->setActive(true);
        $this->get('doctrine.orm.entity_manager')->flush();

        return new JsonResponse(['message' => $this->get('translator')->trans('users.message.active')]);
    }

    /**
     * @param User    $user
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @Route("/{id}/deactivate", requirements={"id": "^\d+$"}, name="app_users_deactivate", methods="PATCH")
     * @Security("has_role('ROLE_AUTH_USERS_ACTIV')")
     */
    public function deactivateAction(User $user, Request $request)
    {
        $this->assertXmlHttpRequest($request);

        $user->setActive(false);
        $this->get('doctrine.orm.entity_manager')->flush();

        return new JsonResponse(['message' => $this->get('translator')->trans('users.message.inactive')]);
    }

    /**
     * @param User    $user
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @Route("/{id}/delete", requirements={"id": "^\d+$"}, name="app_users_delete", methods="DELETE")
     * @Security("has_role('ROLE_AUTH_USERS_DEL')")
     */
    public function deleteAction(User $user, Request $request)
    {
        $this->assertXmlHttpRequest($request);

        $manager = $this->get('doctrine.orm.entity_manager');
        $manager->remove($user);
        $manager->flush();

        return new JsonResponse(['message' => $this->get('translator')->trans('users.message.delete')]);
    }
}