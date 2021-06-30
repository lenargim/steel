<?php

namespace App\Controller;

use App\Entity\AutoPark;
use App\Entity\IconNumTextWidth;
use App\Entity\IconText;
use App\Entity\IconTextWidth;
use App\Form\CallbackMail;
use App\Form\CallbackMailType;
use Doctrine\ORM\EntityManagerInterface;
use SiteBundle\Entity\BottomMenu;
use SiteBundle\Entity\Pages;
use SiteBundle\Entity\TopMenu;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PartController extends AbstractController
{
    /** @var  EntityManagerInterface */
    protected $em;

    /**
     * PartController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }



    /**
     * @param Request $request
     * @param Pages $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function header(Request $request, Pages $page, SessionInterface $session)
    {
        return $this->render('part/header.html.twig', [
            'page' => $page,
            'request' => $request,
            'uri' =>  $request->getRequestUri(),
            'scheme' =>  $request->getScheme(),
        ]);
    }


    /**
     * @param Request $request
     * @param Pages $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function navigation(Request $request, Pages $page)
    {
        $topRepo = $this->getDoctrine()->getManager()->getRepository(TopMenu::class);
        return $this->render('part/navigation.html.twig', [
            'topMenu' => $topRepo->findBy([], ['position' => 'ASC']),
            'page' => $page,
        ]);
    }

    /**
     * @param Request $request
     * @param Pages $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function catalog(Request $request, Pages $page)
    {
        return $this->render('part/catalog-menu.html.twig', [
            'catalog' => $this->getDoctrine()->getManager()->getRepository(Pages\MainPage::class)->findOneBy(['active' => 1]),
            'page' => $page,
        ]);
    }


    /**
     * @deprecated
     * @param Request $request
     * @param Pages $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function footer(Request $request, Pages $page)
    {
        $bottomRepo = $this->getDoctrine()->getManager()->getRepository(BottomMenu::class);
        $bottomMenu = $bottomRepo->findBy([], ['position' => 'ASC']);
        return $this->render('part/footer.html.twig', [
            'bottomMenu' => $bottomMenu,
            'page' => $page,
            'request' => $request,
        ]);
    }


    /**
     * @deprecated
     * @param Request $request
     * @param Pages $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function feedback(Request $request, Pages $page)
    {
        CallbackMailType::$formName = 'feedback_mail';$obj = new CallbackMail();
        $obj->setTypeform('Бесплатная консультация');
        $obj->setUrl($request->getSchemeAndHttpHost() . $this->get('router')->generate($page->getRoute()));
        $form = $this->createForm(CallbackMailType::class, $obj)->createView();
        return $this->render('part/feedback.html.twig', [
            'form' => $form
        ]);
    }


    /**
     * @deprecated
     * @param Pages $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function breadcrumbs(Pages $page)
    {
        $breadcrumbs = [];
        $copy_page = $page;
        do {
            $breadcrumbs[] = [
                'id' =>  $copy_page->getId(),
                'title' => $copy_page->getMenutitle(),
                'route' => $copy_page->getRoute(),
            ];
        } while ($copy_page = $copy_page->getParent());
        krsort($breadcrumbs);

        return $this->render('part/breadcrumbs.html.twig', array(
            'breadcrumbs' => $breadcrumbs,
            'page' => $page
        ));
    }

    /**
     * @param Pages $page
     * @param $count
     * @param $current
     * @param array $getParams
     * @return Response
     */
    public function pagination(Pages $page, $count, $current, $getParams = [])
    {
        $items = [];
        $showed = 4;
        $firstDot = false;
        $secondAllow = false;
        $secondDot = false;
        for ($i = 1; $i <= $count; $i++) {
            if ($i < $showed || $i > $count - $showed + 1) {
                $items[] = [
                    'title' => $i,
                    'active' => $i == $current,
                    'getParams' => $i > 1 ? array_merge($getParams, [
                        'page' => $i
                    ]) : $getParams,
                ];
            } else {
                if ($i >= $current - 1 && $i <= $current + 1) {
                    $items[] = [
                        'title' => $i,
                        'active' => $i == $current,
                        'getParams' => $i > 1 ? array_merge($getParams, [
                            'page' => $i
                        ]) : $getParams,
                    ];
                    if ($firstDot) {
                        $secondAllow = true;
                    }
                } elseif (($i < $current || $i > $current) && !$firstDot) {
                    $firstDot = true;
                    $items[] = [
                        'title' => '...',
                        'active' => false,
                        'getParams' => null
                    ];
                } elseif ($i > $current && $secondAllow && !$secondDot) {
                    $secondDot = true;
                    $items[] = [
                        'title' => '...',
                        'active' => false,
                        'getParams' => null
                    ];

                }
            }
        }

        return $this->render('part/pagination.html.twig', [
            'items' => $items,
            'page' => $page,
            'getParams' => $getParams,
        ]);
    }
}
