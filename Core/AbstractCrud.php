<?php

namespace MJMC\Bundle\CrudBundle\Core;

use AppBundle\Form\TestType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

abstract class AbstractCrud implements CrudInterface
{
    public function isAllowedPostMethod(): Bool
    {
        // TODO: Implement isAllowedPostMethod() method.

        return true;
    }

    public function responseNotAllowedPostMethod(ContainerInterface $container): ?Response
    {
        // TODO: Implement notAllowedPostMethodResponse() method.

        return null;
    }

    public function isAllowedGetMethod(): Bool
    {
        // TODO: Implement isAllowedGetMethod() method.

        return true;
    }

    public function responseNotAllowedGetMethod(ContainerInterface $container): ?Response
    {
        // TODO: Implement notAllowedGetMethodResponse() method.

        return null;
    }

    public function isAllowedPutMethod(): Bool
    {
        // TODO: Implement isAllowedPutMethod() method.

        return true;
    }

    public function responseNotAllowedPutMethod(ContainerInterface $container): ?Response
    {
        // TODO: Implement responseNotAllowedPutMethod() method.

        return null;
    }

    public function isAllowedPatchMethod(): Bool
    {
        // TODO: Implement isAllowedPatchMethod() method.

        return true;
    }

    public function responseNotAllowedPatchMethod(ContainerInterface $container): ?Response
    {
        // TODO: Implement responseNotAllowedPatchMethod() method.

        return null;
    }

    public function isAllowedDeleteMethod(): Bool
    {
        // TODO: Implement isAllowedDeleteMethod() method.

        return true;
    }

    public function responseNotAllowedDeleteMethod(ContainerInterface $container): ?Response
    {
        // TODO: Implement responseNotAllowedDeleteMethod() method.

        return null;
    }

    public function isCreatable(object $entity, ContainerInterface $container): Bool
    {
        // TODO: Implement isCreatable() method.

        return true;
    }

    public function isReadable(object $entity, ContainerInterface $container): Bool
    {
        // TODO: Implement isReadable() method.

        return true;
    }

    public function isUpdatable(object $entity, ContainerInterface $container): Bool
    {
        // TODO: Implement isUpdatable() method.

        return true;
    }

    public function isDeletable(object $entity, ContainerInterface $container): Bool
    {
        // TODO: Implement isDeletable() method.

        return true;
    }
}