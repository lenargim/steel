<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Services\CityService;
use SiteBundle\Entity\Module;
use SiteBundle\Entity\Pages;
use SiteBundle\Service\MailTemplateService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartController extends AbstractController
{

    private MailTemplateService $maier;
    private CityService $cityService;

    /**
     * CartController constructor.
     * @param MailTemplateService $maier
     * @param CityService $cityService
     */
    public function __construct(MailTemplateService $maier, CityService $cityService)
    {
        $this->maier = $maier;
        $this->cityService = $cityService;
    }


    /**
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     */
    public function index(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Pages::class);
        $page = $repo->findOneBy(['route' => $request->get('_route')]);
        $cart = $session->get('cart', []);
        $cartPages = $repo->findBy(['id' => array_keys($cart)]);
        $successPage = $this->getDoctrine()->getManager()->getRepository(Pages\SuccessOrderPage::class)->findOneBy(['active' => 1]);
        /** @var Pages $item */
        foreach ($cartPages as $item) {
            foreach ($cart as $cartItem) {
                $item->setCartCount($cart[$item->getId()]);
            }
        }
        $cityService = $this->cityService;
        $cartItems = array_map(function ($item) use ($request, $cityService) {
            /** @var Pages\CatalogItem $item */
            return [
                'id' => $item->getId(),
                'image' => $item->getWebPath('image'),
                'menutitle' => $item->getMenutitle(),
                'price' => $cityService->getPrice($item, $request),
                'quantity' => $item->getCartCount(),
                'path' => $this->generateUrl($item->getRoute())
            ];
            }, $cartPages);

        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order->setProducts($cartItems);
            $em->persist($order);
            $em->flush();
            $session->set('cart', []);
            $variables = $order->getMailVariables();
            $variables['table'] = $this->renderView('part/table.html.twig', [
                'products' => $cartPages
            ]);
            $this->maier->sendTemplateMail('order_client', [$order->getEmail()], $variables);
            $this->maier->sendTemplateMail('order_admin', [], $variables);
            return $this->render('text/index.html.twig', [
                'page' => $successPage
            ]);
        }
        return $this->render('cart/index.html.twig', [
            'page' => $page,
            'cartItems' => $cartItems,
            'form' =>$form->createView()
        ]);
    }
}
