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
    public static function crudBundle_isReadOnlyEntity(): Bool;

    /**
     * @param ContainerInterface $container
     *
     * @return Response|null
     */
    public static function crudBundle_customResponse_readOnlyEntity(ContainerInterface $container): ?Response;

    /**
     * @return bool
     */
    public static function crudBundle_isMethodAllowed_POST(): Bool;

    /**
     * @return bool
     */
    public static function crudBundle_isMethodAllowed_GET(): Bool;

    /**
     * @return bool
     */
    public static function crudBundle_isMethodAllowed_PUT(): Bool;

    /**
     * @return bool
     */
    public static function crudBundle_isMethodAllowed_PATCH(): Bool;

    /**
     * @return bool
     */
    public static function crudBundle_isMethodAllowed_DELETE(): Bool;

    /**
     * @param ContainerInterface $container
     *
     * @return Response|null
     */
    public static function crudBundle_customResponse_methodNotAllowed(ContainerInterface $container): ?Response;

    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param object|null $user
     * @param Request $request
     * @param ContainerInterface $container
     *
     * @return bool
     */
    public static function crudBundle_isUserGranted(
        AuthorizationCheckerInterface $authorizationChecker,
        $user,
        Request $request,
        ContainerInterface $container
    ): bool;

    /**
     * @param ContainerInterface $container
     *
     * @return Response|null
     */
    public static function crudBundle_customResponse_userNotGranted(ContainerInterface $container): ?Response;

    /**
     * @param FormFactoryInterface $formFactory
     * @param object|null $entity
     * @param Request $request
     * @param ContainerInterface $container
     *
     * @return FormInterface|null
     */
    public static function crudBundle_getForm(
        FormFactoryInterface $formFactory,
        $entity,
        Request $request,
        ContainerInterface $container
    ): ?FormInterface;

    /**
     * @param Request $request
     * @param ContainerInterface $container
     *
     * @return string|null
     */
    public static function crudBundle_getFormType(Request $request, ContainerInterface $container): ?string;

    /**
     * @return bool
     */
    public static function crudBundle_compatibility_MJMCFormAnnotationBundle(): ?bool;

    /**
     * @return bool
     */
    public static function crudBundle_priority_MJMCFormAnnotationBundle(): ?bool;
}
