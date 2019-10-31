<?php

namespace MJMC\Bundle\CrudBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use MJMC\Bundle\CrudBundle\Core\CrudInterface;
use MJMC\Bundle\CrudBundle\Service\ResponseFactory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;

class CrudController extends Controller
{
    /**
     * @param Request $request
     * @param string $entityName
     *
     * @return Response
     */
    public function createAction(Request $request, string $entityName = null): Response
    {
        $entity = new $entityName();

        /** @var $translator TranslatorInterface */
        $translator = $this->get('translator');

        /** @var ContainerInterface $container */
        $container = $this->container;

        if (!$entity instanceof CrudInterface) {
            $message = $translator->trans('response.messages.404', array(), 'MJMCCrudBundle', $request->getLocale());

            return new Response($message, 404);
        }

        if (!$entity->isAllowedPostMethod()) {
            $response = $entity->responseNotAllowedPostMethod($container);

            if ($response instanceof Response) {
                return $response;
            }

            $message = $translator->trans('response.messages.405', array(), 'MJMCCrudBundle', $request->getLocale());

            return new Response($message, 405);
        }

        /*
        if (!$entity->isAllowedUser($authorizationChecker, $this->getUser(), $request, $container)) {
            $response = $entity->notAllowedUserResponse($container);

            if ($response instanceof Response) {
                return $response;
            }

            $message = $translator->trans('response.messages.403', array(), 'MJMCCrudBundle', $request->getLocale());

            return new Response($message, 403);
        }
        */

        $parameters = array('method' => $request->getMethod());

        /** @var FormInterface $form */
        $form = $this->createForm('AppBundle\Form\\'.$entityName.'Type', $parameters);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                /** @var CrudInterface $entity */
                $entity = $form->getData();

                if ($entity->isCreatable($entity, $container)) {
                    /** @var EntityManagerInterface $entityManager */
                    $entityManager = $this->getDoctrine()->getManager();

                    $entityManager->persist($entity);

                    $entityManager->flush();

                    return new Response('success');
                }
            }
        }

        return $entity->getView($request, $form, $container->get('router'));
    }

    /**
     * @param Request $request
     * @param string $entityName
     * @param string $id
     *
     * @return Response
     */
    public function readAction(Request $request, string $entityName, string $id): Response
    {
        $entity = new $entityName();

        /** @var $translator TranslatorInterface */
        $translator = $this->get('translator');

        /** @var ContainerInterface $container */
        $container = $this->container;

        if (!$entity instanceof CrudInterface) {
            $message = $translator->trans('response.messages.404', array(), 'MJMCCrudBundle', $request->getLocale());

            return new Response($message, 404);
        }

        if (!$entity->isAllowedGetMethod()) {
            $response = $entity->responseNotAllowedGetMethod($container);

            if ($response instanceof Response) {
                return $response;
            }

            $message = $translator->trans('response.messages.405', array(), 'MJMCCrudBundle', $request->getLocale());

            return new Response($message, 405);
        }

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        /** @var CrudInterface $entity */
        $entity = $entityManager->find($entityName, $id);

        $parameters = array('method' => $request->getMethod(), 'readonly' => true);

        /** @var FormInterface $form */
        $form = $this->createForm('AppBundle\Form\\'.$entityName.'Type', $entity, $parameters);

        $form->handleRequest($request);

        return $entity->getView($request, $form, $container->get('router'));
    }

    /**
     * @param Request $request
     * @param string $entityName
     * @param string $id
     *
     * @return Response
     */
    public function updateAction(Request $request, string $entityName = null, string $id): Response
    {
        $entity = new $entityName();

        /** @var $translator TranslatorInterface */
        $translator = $this->get('translator');

        /** @var ContainerInterface $container */
        $container = $this->container;

        if (!$entity instanceof CrudInterface) {
            $message = $translator->trans('response.messages.404', array(), 'MJMCCrudBundle', $request->getLocale());

            return new Response($message, 404);
        }

        if (!$entity->isAllowedPutMethod()) {
            $response = $entity->responseNotAllowedPutMethod($container);

            if ($response instanceof Response) {
                return $response;
            }

            $message = $translator->trans('response.messages.405', array(), 'MJMCCrudBundle', $request->getLocale());

            return new Response($message, 405);
        }

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        /** @var CrudInterface $entity */
        $entity = $entityManager->find($entityName, $id);

        $parameters = array('method' => $request->getMethod());

        /** @var FormInterface $form */
        $form = $this->createForm('AppBundle\Form\\'.$entityName.'Type', $entity, $parameters);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $em->persist($form->getData());

                $em->flush();

                return new Response('success');
            }
        }

        return $entity->getView($request, $form, $container->get('router'));
    }

    /**
     * @param Request $request
     * @param string $entityName
     * @param string $id
     *
     * @return Response
     */
    public function deleteAction(Request $request, string $entityName = null, string $id): Response
    {
        $entity = new $entityName();

        /** @var $translator TranslatorInterface */
        $translator = $this->get('translator');

        /** @var ContainerInterface $container */
        $container = $this->container;

        if (!$entity instanceof CrudInterface) {
            $message = $translator->trans('response.messages.404', array(), 'MJMCCrudBundle', $request->getLocale());

            return new Response($message, 404);
        }

        if (!$entity->isAllowedDeleteMethod()) {
            $response = $entity->responseNotAllowedDeleteMethod($container);

            if ($response instanceof Response) {
                return $response;
            }

            $message = $translator->trans('response.messages.405', array(), 'MJMCCrudBundle', $request->getLocale());

            return new Response($message, 405);
        }

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        /** @var CrudInterface $entity */
        $entity = $entityManager->find($entityName, $id);

        $parameters = array('method' => $request->getMethod(), 'readonly' => true);

        /** @var FormInterface $form */
        $form = $this->createForm('AppBundle\Form\\'.$entityName.'Type', $entity, $parameters);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $em->flush();

                return new Response('success');
            }

        }

        return $entity->getView($request, $form, $container->get('router'));
    }

    /**
     * @param Request $request
     * @param string $entityName
     * @param string $id
     *
     * @return Response
     */
    public function crudAction(Request $request, string $entityName = null, string $id = null): Response
    {
        if ('POST' === $request->getMethod()) {
            return $this->createAction($request, $entityName);
        }

        if ('GET' === $request->getMethod()) {
            return $this->readAction($request, $entityName, $id);
        }

        if ('PUT' === $request->getMethod()) {
            return $this->updateAction($request, $entityName, $id);
        }

        if ('DELETE' === $request->getMethod()) {
            return $this->deleteAction($request, $entityName, $id);
        }
    }

}
