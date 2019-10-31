<?php

namespace MJMC\Bundle\CrudBundle\Service;


use MJMC\Bundle\CrudBundle\Common\CrudInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Translation\TranslatorInterface;

class ResponseFactory
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(TranslatorInterface $translator, RequestStack $requestStack)
    {
        $this->translator = $translator;
        $this->request = $requestStack->getMasterRequest();

        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    public function createResponse(FormInterface $form): Response
    {
        /** @var CrudInterface $entity */
        $entity = $form->getData();

        if (!$entity->isHtmlResponseAllowed() && !$entity->isJsonResponseAllowed() && !$entity->isXmlResponseAllowed()) {
            throw new \Exception();
        }

        $accept = $this->request->headers->get('accept');

        if (strpos($accept, 'text/html') && $entity->isHtmlResponseAllowed()) {
            return new Response();
        }

        if (strpos($accept, 'application/json') && $entity->isJsonResponseAllowed()) {
            return new Response();
        }

        if (strpos($accept, 'application/xml') && $entity->isXmlResponseAllowed()) {
            return new Response();
        }

        $array = array();

        foreach ($form as $key => $value) {
            $array[$key] = $value->getData();
        }

        return new Response($this->translator->trans('response.messages.406', array(), 'MJMCCrudBundle', $this->request->getLocale()), 406);
    }
}