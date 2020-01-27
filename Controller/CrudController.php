<?php

namespace MJMC\Bundle\CrudBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use MJMC\Bundle\CrudBundle\Core\CrudInterface;
use MJMC\Bundle\CrudBundle\Service\CrudUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CrudController extends Controller
{
    /**
     * @param Request $request
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function check(Request $request){
        /** @var CrudUtils $crudUtils */
        $crudUtils = $this->container->get('mjmc_crud.crud_utils');

        $crudUtils->log('info', 'MJMCCrudBundle initialized');

        /*
        if (!$crudUtils->isValidMethod($request, 'POST')) {

             *
             *
             *
             *
            throw new Exception('Method no valid.');
        }
        */

        $entityName = $request->get('entityName', null);

        $entityNamespace = $request->get('entityNamespace', null);

        $context = array(
            'parameters' => array('entityName' => $entityName, 'entityNamespace' => $entityNamespace),
            'configurations' => array(
                'debug' => $this->container->getParameter('mjmc_crud.debug'),
                'throws' => $this->container->getParameter('mjmc_crud.throws')
            ),
        );

        $crudUtils->log('info', 'MJMCCrudBundle initialized', $context);

        /** @var CrudInterface $entity */
        $entity = $crudUtils->getEntity($entityName, $entityNamespace);

        if (!$entity instanceof CrudInterface) {
            /**
             *
             *
             *
             */
            throw new Exception('$entity no valid.');
        }

        if ($request->isXmlHttpRequest()){




        }

        if ($entity::crudBundle_isReadOnlyEntity()) {
            $crudUtils->logAndThrow('critical', 'Execution stopped. ');

            /** @var Response|null $response */
            $response = $entity::crudBundle_customResponse_readOnlyEntity($this->container);

            if ($response) {
                return $response;
            }

            /**
             *
             *
             *
             */
            throw new Exception('Read only.');
        }

        if (!$entity::crudBundle_isMethodAllowed_POST()) {
            $crudUtils->logAndThrow('critical', 'Execution stopped. ');

            /** @var Response|null $response */
            $response = $entity::crudBundle_customResponse_methodNotAllowed($this->container);

            if ($response) {
                return $response;
            }

            /**
             *
             *
             *
             */
            throw new Exception('Method not allowed.');
        }

        /** @var AuthorizationCheckerInterface $authorizationChecker */
        $authorizationChecker = $this->get('security.authorization_checker');

        if (!$entity::crudBundle_isUserGranted($authorizationChecker, $this->getUser(), $request, $this->container)) {
            $crudUtils->logAndThrow('info', 'The current user does not have the permissions to access this resource.');

            $response = $entity::crudBundle_customResponse_userNotGranted($this->container);

            if ($response instanceof Response) {
                return $response;
            }

            /**
             *
             *
             *
             */
            throw new Exception('User not granted.');
        }









        if ('POST' !== $request->getMethod()) {

            $entity = $this->getDoctrine()->getRepository('AppBundle\Entity\\'.$entityName)->find($request->get('id'));
        } else {
            $entity = (new \ReflectionClass($entity))->newInstance();
        }








        /** @var FormInterface $form */
        $form = $crudUtils->getForm($entity, $request);

        $form->handleRequest($request);

        $crudUtils->log('info', 'Checking submitted form...');

        return $form;





        if ($form->isSubmitted()) {
            $crudUtils->log('info', 'Form submitted successfully. Checking validation...');

            if ($form->isValid()) {
                $crudUtils->log('info', 'Form validated successfully. Checking additional requirements to perform the operation.');

                /** @var CrudInterface $entity */
                $entity = $form->getData();

                if (true) {
                    $crudUtils->log('info', 'Additional requirements met successfully.');

                    /** @var EntityManagerInterface $entityManager */
                    $entityManager = $this->getDoctrine()->getManager();






                    /**
                     *
                     *
                     *
                     */
                    throw new Exception('ok');
                }

                $crudUtils->log('info', 'The additional requirements to perform the operation are not met.');

                /**
                 *
                 *
                 *
                 */
                throw new Exception('ok');
            }

            /** @var array $context */
            $context = array();

            $crudUtils->log('info', 'The form submitted is not valid.', $context);

            /**
             *
             *
             *
             */
            throw new Exception('ok');
        }

        $crudUtils->log('info', 'No form has been submitted.');

        /**
         *
         *
         *
         */
        throw new Exception('ok');
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws Exception
     */
    public function createAction(Request $request): Response
    {

    }


    /**
     * @param Request $request
     *
     * @return Response
     */
    public function cReadAction(Request $request): Response
    {

    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function readAction(Request $request): Response
    {
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function updateAction(Request $request): Response
    {
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function deleteAction(Request $request): Response
    {
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws Exception
     */
    public function crudAction(Request $request): Response
    {
        /** @var CrudUtils $crudUtils */
        $crudUtils = $this->container->get('mjmc_crud.crud_utils');

        /** @var FormInterface $form */
        $form = $this->check($request);

        if ($form instanceof Response){
            return $form;
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            if ('POST' === $request->getMethod()){
                $entityManager->persist($form->getData());
            }

            if ('DELETE' === $request->getMethod()){
                $entityManager->remove($form->getData());
            }

            try {
                $entityManager->flush();

                return new Response('success');
            } catch (\Exception $exception) {
                return new Response('error');
            }
        }

        $rc = new \ReflectionClass($form->getData());

        $parameters = array(
            'form' => $form->createView(),
            'entityName' => $rc->getShortName(),
            'action' => $this->generateUrl('mjmc_crud', array('entityName' => $rc->getShortName(), 'id' => $request->get('id', null))),
            'method' => $request->getMethod(),
        );

        return $this->render('@App/base/modal.html.twig', $parameters);







        /*
        $id = $request->get('id', null);

        if ('POST' === $request->getMethod()) {
            return $this->createAction($request);
        }

        if ('GET' === $request->getMethod()) {
            if (null === $id){
                return $this->cReadAction($request);
            }

            return $this->readAction($request);
        }

        if ('PUT' === $request->getMethod() || 'PATCH' === $request->getMethod()) {
            return $this->updateAction($request);
        }

        if ('DELETE' === $request->getMethod()) {
            return $this->deleteAction($request);
        }

        return new Response();

        */
    }
}
