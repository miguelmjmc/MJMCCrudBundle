<?php

namespace MJMC\Bundle\CrudBundle\Core;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

interface CrudInterface
{
    /**
     * @return bool
     */
    public function isAllowedPostMethod(): Bool;

    /**
     * @param ContainerInterface $container
     *
     * @return Response|null
     */
    public function responseNotAllowedPostMethod(ContainerInterface $container): ?Response;

    /**
     * @return bool
     */
    public function isAllowedGetMethod(): Bool;

    /**
     * @param ContainerInterface $container
     *
     * @return Response|null
     */
    public function responseNotAllowedGetMethod(ContainerInterface $container): ?Response;

    /**
     * @return bool
     */
    public function isAllowedPutMethod(): Bool;

    /**
     * @param ContainerInterface $container
     *
     * @return Response|null
     */
    public function responseNotAllowedPutMethod(ContainerInterface $container): ?Response;
    /**
     * @return bool
     */
    public function isAllowedPatchMethod(): Bool;

    /**
     * @param ContainerInterface $container
     *
     * @return Response|null
     */
    public function responseNotAllowedPatchMethod(ContainerInterface $container): ?Response;

    /**
     * @return bool
     */
    public function isAllowedDeleteMethod(): Bool;

    /**
     * @param ContainerInterface $container
     *
     * @return Response|null
     */
    public function responseNotAllowedDeleteMethod(ContainerInterface $container): ?Response;
















    /*
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param object $user
     * @param Request $request
     * @param ContainerInterface $container
     *
     * @return bool
     *
    public function isAllowedUser(AuthorizationCheckerInterface $authorizationChecker, object $user = null, Request $request, ContainerInterface $container): bool;

    /**
     * @param ContainerInterface $container
     *
     * @return Response|null
     *
    public function notAllowedUserResponse(ContainerInterface $container): ?Response;
    */

    /**
     * @param object $entity
     * @param ContainerInterface $container
     *
     * @return bool
     */
    public function isCreatable(object $entity, ContainerInterface $container): Bool;

    /**
     * @param object $entity
     * @param ContainerInterface $container
     *
     * @return bool
     */
    public function isReadable(object $entity, ContainerInterface $container): Bool;

    /**
     * @param object $entity
     * @param ContainerInterface $container
     *
     * @return bool
     */
    public function isUpdatable(object $entity, ContainerInterface $container): Bool;

    /**
     * @param object $entity
     * @param ContainerInterface $container
     *
     * @return bool
     */
    public function isDeletable(object $entity, ContainerInterface $container): Bool;


    /*
     * @param FormFactoryInterface $formFactory
     * @param object $entity
     * @param Request $request
     * @param ContainerInterface $container
     *
     * @return FormInterface|null
     *
    public function getForm(FormFactoryInterface $formFactory, object $entity, Request $request, ContainerInterface $container): ?FormInterface;
    */

    public function getView(Request $request, FormInterface $form, RouterInterface $router): Response;
}