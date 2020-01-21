<?php

namespace MJMC\Bundle\CrudBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use MJMC\Bundle\CrudBundle\Core\CrudInterface;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

class CrudUtils
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var bool
     */
    private $debug;

    /**
     * @var bool
     */
    private $throws;

    /**
     * CrudUtils constructor.
     *
     * @param KernelInterface $kernel
     * @param LoggerInterface $logger
     * @param ContainerInterface $container
     * @param FormFactoryInterface $formFactory
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        ContainerInterface $container,
        EntityManagerInterface $entityManager,
        FormFactoryInterface $formFactory,
        KernelInterface $kernel,
        LoggerInterface $logger
    ) {
        $this->container = $container;

        $this->entityManager = $entityManager;

        $this->formFactory = $formFactory;

        $this->kernel = $kernel;

        $this->logger = $logger;

        $this->debug = $this->container->getParameter('mjmc_crud.debug');

        $this->throws = $this->container->getParameter('mjmc_crud.throws');
    }

    /**
     * @param string $level
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function log(string $level, string $message, array $context = array()): void
    {
        if ($this->debug && $this->kernel->isDebug()) {
            $this->logger->log($level, $message, $context);
        }
    }

    /**
     * @param string $level
     * @param string $message
     * @param array $context
     *
     * @return void
     *
     * @throws Exception
     */
    public function logAndThrow(string $level, string $message, array $context = array()): void
    {
        if ($this->debug && $this->kernel->isDebug()) {
            $this->logger->log($level, $message, $context);

            if ($this->throws) {
                $this->logger->info('Throws set to true. Throwing exception...');

                $message = str_replace('"', '', $message);

                throw new Exception($message);
            }
        }
    }

    /**
     * @param Request $request
     * @param string $method
     *
     * @return bool
     *
     * @throws Exception
     */
    public function isValidMethod(Request $request, string $method): bool
    {
        $method = strtoupper($method);

        if ($request->getMethod() === $method) {
            return true;
        }

        $this->logAndThrow(
            'critical',
            'Execution stopped. Invalid method, this action only support the ' . $method . ' method.'
        );

        return false;
    }


    /**
     * @param string $class
     *
     * @return bool
     *
     * @throws Exception
     */
    public function classExist(string $class): bool
    {
        if (class_exists($class)) {
            return true;
        }

        $this->logAndThrow('critical', 'Execution stopped. Class "' . $class . '" does not exist.');

        return false;
    }

    /**
     * @param $class
     *
     * @return bool
     *
     * @throws Exception
     */
    public function isEntity(string $class): bool
    {
        if (!$this->entityManager->getMetadataFactory()->isTransient($class)) {
            return true;
        }

        $this->logAndThrow('critical', 'Execution stopped. Class "' . $class . '" does not a doctrine entity.');

        return false;
    }

    /**
     * @param string $class
     * @param string $interface
     *
     * @return bool
     *
     * @throws Exception
     */
    public function implement(string $class, string $interface): bool
    {
        $reflectionClass = new ReflectionClass($class);

        if ($reflectionClass->implementsInterface($interface)) {
            return true;
        }

        $this->logAndThrow(
            'critical',
            'Execution stopped. Class "' . $class . '" does not an implementation of "' . $interface . '".'
        );

        return false;
    }

    /**
     * @return string
     */
    public function getBaseNamespace(): string
    {
        return 3 >= (int)$this->kernel::VERSION[0] && '.' === $this->kernel::VERSION[1] ? 'AppBundle\\' : 'App\\';
    }

    public function isBundleAvailable(string $bundleName): bool
    {
        $bundles = $this->container->getParameter('kernel.bundles');

        if (isset($bundles[$bundleName])) {
            return true;
        }

        return false;
    }

    /**
     * @param string $formTypeClass
     * @param object|null $entity
     *
     * @return FormInterface
     *
     * @throws Exception
     */
    public function buildForm(string $formTypeClass, object $entity = null): FormInterface
    {
        if ($this->classExist($formTypeClass)) {
            if ($this->implement($formTypeClass, FormTypeInterface::class)) {
                /** @var FormInterface $form */
                $form = $this->formFactory->create($formTypeClass, $entity);

                $this->log('info', 'FormType "' . $formTypeClass . '" built successfully.');

                return $form;
            }

            throw new Exception(
                'Execution stopped. Class ' . $formTypeClass . ' does not an implementation of ' . FormTypeInterface::class . '.'
            );
        }

        throw new Exception('Execution stopped. Class ' . $formTypeClass . ' does not exist.');
    }

    /**
     * @param string $entityClass
     *
     * @return FormInterface|null
     */
    public function buildFormFromAnnotation(string $entityClass): ?FormInterface
    {
        $this->log('info', $entityClass);

        return $this->formFactory->createBuilder()->getForm();
    }

    /**
     * @param string $entityName
     * @param string $entityNamespace
     *
     * @return CrudInterface|null
     *
     * @throws Exception
     */
    public function getEntity(?string $entityName, ?string $entityNamespace): ?CrudInterface
    {
        /** @var array $context */
        $context = array('parameters' => array('entityName' => $entityName, 'entityNamespace' => $entityNamespace));

        $this->log('info', 'Resolving entity...', $context);

        if (null === $entityName) {
            $this->logAndThrow('critical', 'Execution stopped. The "entityName" parameter cannot be null.');

            return null;
        }

        if (null === $entityNamespace) {
            $entityNamespace = $this->getBaseNamespace() . 'Entity\\';

            $this->log('info', 'Entity namespace not provided, "' . $entityNamespace . '" used as default value.');
        }

        /** @var string $entityClass */
        $entityClass = $entityNamespace . $entityName;

        if (!$this->classExist($entityClass)) {
            return null;
        }

        if (!$this->isEntity($entityClass)) {
            return null;
        }

        if (!$this->implement($entityClass, CrudInterface::class)) {
            return null;
        }

        /** @var ReflectionClass $entityReflectionClass */
        $entityReflectionClass = new ReflectionClass($entityClass);

        /** @var CrudInterface $entity */
        $entity = $entityReflectionClass->newInstanceWithoutConstructor();

        $this->log('info', 'Entity "' . $entityClass . '" loaded successfully.');

        return $entity;
    }

    /**
     * @param CrudInterface $entity
     * @param Request $request
     *
     * @return FormInterface|null
     *
     * @throws Exception
     */
    public function getForm(CrudInterface $entity, Request $request): FormInterface
    {
        $parameters = array('method' => $request->getMethod());

        if ('GET' === $request->getMethod() || 'DELETE' === $request->getMethod()) {
            $parameters['attr'] = array('readonly' => true);
        }








        /** @var ReflectionClass $entityReflectionClass */
        $entityReflectionClass = new ReflectionClass($entity);

        /** @var array $context */
        $context = array('parameters' => array('entityClass' => $entityReflectionClass->getName()));

        $this->log('info', 'Resolving form...', $context);

        /** @var FormInterface $form */
        $form = $entity::crudBundle_getForm($this->formFactory, $entity, $request, $this->container);

        if ($form instanceof FormInterface) {
            $this->log('info', 'Form provided. Building form...');

            $this->log('info', 'Form built successfully.');

            return $form;
        }

        $this->log('info', 'No form was provided. Checking formType...');

        /** @var string|null $formTypeClass */
        $formTypeClass = $entity::crudBundle_getFormType($request, $this->container);

        if (null !== $formTypeClass) {
            /** @var array $context */
            $context = array('parameters' => array('formTypeClass' => $formTypeClass));

            $this->log('info', 'FormType provided. Building form...', $context);

            return $this->buildForm($formTypeClass, $entity);
        }

        $this->log('info', 'No formType was provided.');

        $formTypeClass = $this->getBaseNamespace() . 'Form\\' . $entityReflectionClass->getShortName() . 'Type';

        if (!$entity::crudBundle_compatibility_MJMCFormAnnotationBundle()) {
            $this->log('info', '"MJMCFormAnnotationBundle" compatibility set to false.');

            $this->log(
                'info',
                'No form or formType was provided, "' . $formTypeClass . '" used as default formType. Building form...'
            );

            return $this->buildForm($formTypeClass, $entity);
        }

        $this->log('info', '"MJMCFormAnnotationBundle" compatibility set to true. Checking availability...');

        if (!$this->isBundleAvailable('MJMCFormAnnotationBundle')) {
            $this->log('info', '"MJMCFormAnnotationBundle" is not installed.');

            $this->log(
                'info',
                'No form or formType was provided, "' . $formTypeClass . '" used as default formType. Building form...'
            );

            return $this->buildForm($formTypeClass, $entity);
        }

        $this->log('info', '"MJMCFormAnnotationBundle" is installed. Checking priority...');

        if ($entity::crudBundle_priority_MJMCFormAnnotationBundle()) {
            $this->log('info', '"MJMCFormAnnotationBundle" priority set to true.');

            /** @var FormInterface|null $form */
            $form = $this->buildFormFromAnnotation($entityReflectionClass->getName());

            if ($form instanceof FormInterface) {
                return $form;
            }

            $this->log(
                'info',
                'No form or formType was provided, "' . $formTypeClass . '" used as default formType. Building form...'
            );

            return $this->buildForm($formTypeClass, $entity);
        }

        $this->log('info', '"MJMCFormAnnotationBundle" priority set to false.');

        $this->log(
            'info',
            'No form or formType was provided, "' . $formTypeClass . '" used as default formType. Building form...'
        );

        if (class_exists($formTypeClass)) {
            $formReflectionClass = new ReflectionClass($formTypeClass);

            if ($formReflectionClass->implementsInterface(FormTypeInterface::class)) {
                /** @var FormInterface $form */
                $form = $this->formFactory->create($formTypeClass, $entity, $parameters);

                $this->log('info', 'FormType "' . $formTypeClass . '" built successfully.');

                return $form;
            }

            $this->log(
                'info',
                'Class "' . $formTypeClass . '" does not an implementation of ' . FormTypeInterface::class . '.'
            );
        } else {
            $this->log('info', 'Class "' . $formTypeClass . '" does not exist.');
        }

        /** @var FormInterface|null $form */
        $form = $this->buildFormFromAnnotation($entityReflectionClass->getName());

        if ($form instanceof FormInterface) {
            return $form;
        }

        throw new Exception('Execution stopped. Some form or formType must be provided.');
    }
}
