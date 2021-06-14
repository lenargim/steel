<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\CallbackMail;
use App\Form\CallbackMailType;
use App\Services\CityService;
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
    protected EntityManagerInterface $em;
    protected CityService $cityService;

    /**
     * PartController constructor.
     * @param EntityManagerInterface $em
     * @param CityService $cityService
     */
    public function __construct(EntityManagerInterface $em, CityService $cityService)
    {
        $this->em = $em;
        $this->cityService = $cityService;
    }


    /**
     * проверяем надо ли опрашивать пользователя про город
     *
     * @param Request $request
     * @return Response
     */
    public function askUserCity(Request $request)
    {
        // есть ли get параметр что устанавливаем куку по домену
        if ($request->get('selected_city', false)) {
            $setCityCookieId = $this->cityService->getHostCity($request)->getId();
        } else {
            $setCityCookieId = null;
        }
        return $this->render('part/askSity.html.twig', array(
            'askedCity' => $setCityCookieId ? null : $this->cityService->askedUserCity($request),
            'setCityCookieId' => $setCityCookieId,
            'uri' =>  $request->getRequestUri(),
            'scheme' =>  $request->getScheme()
        ));
    }



    /**
     * @param Request $request
     * @param Pages $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function header(Request $request, Pages $page, SessionInterface $session)
    {
        $cart = $session->get('cart', []);
        $cartItems = [];
        $cartCount = 0;
        foreach ($cart as $id => $quantity) {
            $cartItems[] = [
                'id' => $id,
                'quantity' => $quantity
            ];
            $cartCount += $quantity;
        }
        $cartPage = $this->em->getRepository(Pages\CartPage::class)->findOneBy(['active' => 1]);


        $city = $this->cityService->getHostCity($request);
        return $this->render('part/header.html.twig', [
            'page' => $page,
            'request' => $request,
            'uri' =>  $request->getRequestUri(),
            'scheme' =>  $request->getScheme(),
            'city' => $city,
            'cartPage' => $cartPage,
            'cartItems' => $cartItems,
            'cartCount' => $cartCount,
        ]);
    }


    /**
     * @param Request $request
     * @param Pages $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function navigation(Request $request, Pages $page)
    {
        $topMenu = $this->em->getRepository(TopMenu::class)->findBy([], ['position' => 'ASC']);
        $searchPage = $this->em->getRepository(Pages\SearchPage::class)->findOneBy(['active' => 1]);
        return $this->render('part/navigation.html.twig', [
            'topMenu' => $topMenu,
            'catalog' => $this->em->getRepository(Pages\CatalogMain::class)->findOneBy(['active' => 1]),
            'page' => $page,
            'searchPage' => $searchPage,
        ]);
    }

    /**
     * @param Request $request
     * @param string $type
     * @return Response
     */
    public function cities(Request $request, $type = 'city-list')
    {
        $cities = $this->getDoctrine()->getManager()->getRepository(City::class)->findAll();
        $city = $this->cityService->getHostCity($request);
        if ($type == 'city-list-mobile') {
            return $this->render('part/city-list-mobile.html.twig', [
                'cities' => $cities,
                'city' => $city,
                'uri' => $request->getRequestUri()
            ]);
        } else {
            return $this->render('part/city-list.html.twig', [
                'cities' => $cities,
                'city' => $city,
                'uri' => $request->getRequestUri()
            ]);
        }
    }

    /**
     * @param Request $request
     * @param Pages $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function catalog(Request $request, Pages $page)
    {
        $topMenu = $this->em->getRepository(TopMenu::class)->findBy([], ['position' => 'ASC']);
        $searchPage = $this->em->getRepository(Pages\SearchPage::class)->findOneBy(['active' => 1]);
        return $this->render('part/navigation-mobile.html.twig', [
            'topMenu' => $topMenu,
            'catalog' => $this->em->getRepository(Pages\CatalogMain::class)->findOneBy(['active' => 1]),
            'page' => $page,
            'searchPage' => $searchPage,
        ]);
    }

    /**
     * @param Request $request
     * @param Pages $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function navigationMobile(Request $request, Pages $page)
    {
        $topMenu = $this->em->getRepository(TopMenu::class)->findBy([], ['position' => 'ASC']);
        $searchPage = $this->em->getRepository(Pages\SearchPage::class)->findOneBy(['active' => 1]);
        return $this->render('part/navigation-mobile.html.twig', [
            'topMenu' => $topMenu,
            'catalog' => $this->em->getRepository(Pages\CatalogMain::class)->findOneBy(['active' => 1]),
            'page' => $page,
            'searchPage' => $searchPage,
        ]);
    }

    /**
     * @param Request $request
     * @param Pages $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function footer(Request $request, Pages $page)
    {
        $pageRepo = $this->em->getRepository(Pages::class);
        $city = $this->cityService->getHostCity($request);
        return $this->render('part/footer.html.twig', [
            'page' => $page,
            'city' => $city,
        ]);
    }

    public function metrika(Request $request, $type)
    {
        $city = $this->cityService->getHostCity($request);
        return $this->render('part/metrika.html.twig', array(
            'type' => $type,
            'metrika' => $type == 'headmetrika' ? $city->getHeadmetrika() : $city->getYandexmetrika(),
            'city' => $city,
        ));
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
