<?php

namespace MJMC\Bundle\CrudBundle\Core;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

trait CrudTrait
{
    public static function crudBundle_isReadOnlyEntity(): Bool
    {
        // TODO: Implement crudBundle_isReadOnlyEntity() method.

        return false;
    }

    public static function crudBundle_customResponse_readOnlyEntity(ContainerInterface $container): ?Response
    {
        // TODO: Implement crudBundle_customResponse_readOnlyEntity() method.

        return null;
    }

    public static function crudBundle_isMethodAllowed_GET(): Bool
    {
        // TODO: Implement crudBundle_isMethodAllowed_GET() method.

        return true;
    }

    public static function crudBundle_isMethodAllowed_POST(): Bool
    {
        // TODO: Implement crudBundle_isMethodAllowed_POST() method.

        return true;
    }

    public static function crudBundle_isMethodAllowed_PUT(): Bool
    {
        // TODO: Implement crudBundle_isMethodAllowed_PUT() method.

        return true;
    }

    public static function crudBundle_isMethodAllowed_PATCH(): Bool
    {
        // TODO: Implement crudBundle_isMethodAllowed_PATCH() method.

        return true;
    }

    public static function crudBundle_isMethodAllowed_DELETE(): Bool
    {
        // TODO: Implement crudBundle_isMethodAllowed_DELETE() method.

        return true;
    }

    public static function crudBundle_customResponse_methodNotAllowed(ContainerInterface $container): ?Response
    {
        // TODO: Implement crudBundle_customResponse_methodNotAllowed() method.

        return null;
    }

    public static function crudBundle_isUserGranted(
        AuthorizationCheckerInterface $authorizationChecker,
        $user,
        Request $request,
        ContainerInterface $container
    ): bool {
        // TODO: Implement crudBundle_isUserGranted() method.

        return true;
    }

    public static function crudBundle_customResponse_userNotGranted(ContainerInterface $container): ?Response
    {
        // TODO: Implement crudBundle_customResponse_userNotGranted() method.

        return null;
    }

    public static function crudBundle_getForm(
        FormFactoryInterface $formFactory,
        $entity,
        Request $request,
        ContainerInterface $container
    ): ?FormInterface {
        // TODO: Implement crudBundle_getForm() method.

        return null;
    }

    public static function crudBundle_getFormType(Request $request, ContainerInterface $container): ?string
    {
        // TODO: Implement crudBundle_getFormType() method.

        return null;
    }

    public static function crudBundle_compatibility_MJMCFormAnnotationBundle(): ?bool
    {
        // TODO: Implement crudBundle_compatibility_MJMCFormAnnotationBundle() method.

        return true;
    }

    public static function crudBundle_priority_MJMCFormAnnotationBundle(): ?bool
    {
        // TODO: Implement crudBundle_priority_MJMCFormAnnotationBundle() method.

        return false;
    }
}
