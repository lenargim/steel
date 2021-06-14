<?php

namespace App\Controller;

use SiteBundle\Controller\BasePageController;
use SiteBundle\Entity\Module;
use SiteBundle\Entity\Pages;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends BasePageController
{
    public function index(Request $request)
    {
        $this->setPage($request);
        /** @var Pages $catalog */
        $catalog = $this->getDoctrine()->getManager()->getRepository(Pages\CatalogMain::class)->findOneBy(['active' => 1]);
        /** @var Pages $catalog */
        $service = $this->getDoctrine()->getManager()->getRepository(Pages\ServiceListPage::class)->findOneBy(['active' => 1]);
        $slidersCatalog = $this->getDoctrine()->getManager()->getRepository(Pages\CatalogItem::class)->findBy(['onmain' => 1], ['lft' => 'ASC']);
        $slidersService = $this->getDoctrine()->getManager()->getRepository(Pages\ServiceItem::class)->findBy(['onmain' => 1], ['lft' => 'ASC']);
        $articles = $this->getDoctrine()->getManager()->getRepository(Pages\CatalogArticle::class)->findBy(['popular' => 1], ['lft' => 'ASC']);
        $items = $this->getDoctrine()->getManager()->getRepository(Pages\CatalogItem::class)->findBy(['popular' => 1], ['lft' => 'ASC']);

        return $this->render('main/index.html.twig', [
            'page' => $this->page,
            'city' => $this->city,
            'catalog' => $catalog,
            'service' => $service,
            'articles' => $articles,
            'items' => $items,
            'sliders' => array_merge($slidersCatalog, $slidersService),
        ]);
    }
}
