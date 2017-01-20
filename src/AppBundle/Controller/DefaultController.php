<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller {

    /**
     * @Route("/def", name="homepage_old")
     */
    public function defaultAction(Request $request) {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
                    'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request) {


        $api = $this->getApi();
        $login = null;
        try {
            $login = $api->getLogin();
        } catch (\Yandex\Disk\Exception\DiskRequestException $ex) {
            //return $this->authAction( $request);
        }


        $data = [];
        $form = $this->createFormBuilder($data)
                ->add('path', FormType\TextType::class)
                ->add('file', FormType\FileType::class)
                ->add('send', FormType\SubmitType::class)
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dataForm = $form->getData();


            $api->uploadFile($dataForm['file']->getRealPath(), $dataForm['path'], $dataForm['file']->getClientOriginalName());
            return $this->redirectToRoute('homepage', array(), 301);
        }


        return $this->render('AppBundle:default:index.html.twig', [
                    'form' => $form->createView(),
                    'login' => $login,
        ]);
    }

    /**
     * 
     * @Route("/auth", name="auth")
     * @param Request $request
     * @return type
     */
    public function authAction(Request $request) {

        $api = $this->getApi();
        $api->authRedirect();
    }

    /**
     * @Route("/callback", name="callback")
     * @param Request $request
     */
    public function authCallBackAction(Request $request) {

        $api = $this->getApi();
        $token = $api->getTokenByCodeCallBack($request->get('code'));
        

        $cookie = new Cookie(\AppBundle\Service\Api::NAME_COOKIE_TOKEN, $token, 0);

        $response = new RedirectResponse('/');
        $response->headers->setCookie($cookie);

        $response->sendHeaders();
        return $response;
    }

    /**
     * 
     * @return \AppBundle\Service\Api
     */
    private function getApi() {
        return $this->get('service_api');
    }

}
