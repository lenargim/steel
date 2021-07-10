<?php

namespace App\Controller;

use App\Entity\Letter;
use App\Form\CallbackMail;
use App\Form\CallbackMailType;
use App\Form\MessageMail;
use App\Form\MessageMailType;
use Doctrine\ORM\EntityManagerInterface;
use SiteBundle\Entity\Pages;
use SiteBundle\Helper\DataHandler;
use SiteBundle\Service\MailTemplateService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ServiceSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Router;

class AjaxController extends AbstractController implements ServiceSubscriberInterface
{

    /** @var MailTemplateService */
    private $maier;

    /** @var  EntityManagerInterface */
    protected $em;

    /**
     * AjaxController constructor.
     * @param MailTemplateService $maier
     * @param EntityManagerInterface $em
     */
    public function __construct(MailTemplateService $maier, EntityManagerInterface $em)
    {
        $this->maier = $maier;
        $this->em = $em;
    }


    /**
     * @param Request $request
     * @param $type
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/ajax/get_popup/{type}", name="app_main.ajax_get_popup")
     */
    public function getPopup(Request $request, $type)
    {
        $form = null;
        $em = $this->getDoctrine()->getManager();
        $route = $request->get('route', null);

        if ($type == 'callback') {
            CallbackMailType::$formName = 'callback_mail';
            $obj = new CallbackMail();
            $obj->setTypeform('Заказать звонок');
            if ($route) {
                $obj->setUrl($request->getSchemeAndHttpHost() . $this->get('router')->generate($route));
            }
            $form = [
                'class' => 'js-callback-form',
                'title' => 'forms_callback_title',
                'type' => 'callback',
                'form' => $this->createForm(CallbackMailType::class, $obj)->createView()
            ];
        }

        if ($type == 'message') {
            $obj = new MessageMail();
            $obj->setTypeform('Узнать стоимость');
            if ($route) {
                $obj->setUrl($request->getSchemeAndHttpHost() . $this->get('router')->generate($route));
            }
            $form = [
                'class' => 'js-message-form',
                'title' => 'forms_message_title',
                'type' => 'message',
                'form' => $this->createForm(MessageMailType::class, $obj)->createView()
            ];
        }

        return $this->render('ajax/' . $type . '.html.twig', [
            'form' => $form,
        ]);
    }



    /**
     * @Route("/ajax/ajax_validform/{type}", name="app_main.ajax_ajax_validform")
     */
    public function ajaxValidForm(Request $request, $type)
    {
        $form = null;
        $forms = [
            'message' => [
                'obj' => 'App\Form\MessageMail',
                'form' => 'App\Form\MessageMailType',
                'mail_template' => 'message_mail',
                'send_mail' => true,
                'variables' => [
                    'name' => 'getName',
                    'email' => 'getEmail',
                    'phone' => 'getPhone',
                    'text' => 'getText',
                    'page' => 'getUrl',
                    'type' => 'getType',
                ]
            ],
            'callback' => [
                'obj' => 'App\Form\CallbackMail',
                'form' => 'App\Form\CallbackMailType',
                'mail_template' => 'callback_mail',
                'send_mail' => true,
                'variables' => [
                    'name' => 'getName',
                    'phone' => 'getPhone',
                    'type' => 'getType',
                    'page' => 'getUrl',
                ]
            ],
        ];

        if (isset($forms[$type])) {
            $em = $this->getDoctrine()->getManager();
            /** @var $router \Symfony\Component\Routing\Router */
            $router = $this->container->get('router');
            $obj_name = $forms[$type]['obj'];
            $form_class = $forms[$type]['form'];
            $obj = new $obj_name();

            $form = $this->createForm($form_class, $obj);
            $form->handleRequest($request);

            if ($form->isValid()) {
                if (!empty($forms[$type]['send_mail'])) {
                    $variables = [];
                    foreach ($forms[$type]['variables'] as $name => $method) {
                        if (method_exists($obj, $method)) {
                            $variables[$name] = $obj->$method();
                        } else {
                            $variables[$name] = $method;
                        }
                    }
                    $this->maier->sendTemplateMail($forms[$type]['mail_template'], [], $variables);
                    if ($type == 'callback'  || $type == 'message' ) {
                        $entity = Letter::getInstanceByMail($obj);
                        $em->persist($entity);
                        $em->flush();
                    }
                }
                if (!empty($forms[$type]['submit'])) {
                    return new JsonResponse(['submit' => true]);
                }
                return new JsonResponse(['ok' => true]);
            } else {
                $error = DataHandler::getErrorMessages($form);
                return new JsonResponse(['error' => $error]);
            }
        }

        return new JsonResponse([]);
    }
}
