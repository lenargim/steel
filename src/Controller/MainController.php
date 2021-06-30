<?php

namespace App\Controller;

use App\Entity\Review;
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
        $portfolio = $this->getDoctrine()->getManager()->getRepository(Pages\PortfolioListPage::class)->findOneBy(['active' => 1]);
        $articles = $this->getDoctrine()->getManager()->getRepository(Pages\CatalogArticle::class)->findBy(['onmain' => 1], ['lft' => 'ASC']);
        $reviews = $this->getDoctrine()->getManager()->getRepository(Review::class)->findBy([], ['position' => 'DESC'], 5);
        $reviewListPage = $this->getDoctrine()->getManager()->getRepository(Pages\ReviewsListPage::class)->findOneBy(['active' => 1]);
        $catalogMainPage = $this->getDoctrine()->getManager()->getRepository(Pages\CatalogMain::class)->findOneBy(['active' => 1]);
        $interios = $this->getDoctrine()->getManager()->getRepository(Pages\InteriorItemPage::class)->findBy([], ['year' => 'DESC'], 5);

        return $this->render('main/index.html.twig', [
            'page' => $this->page,
            'catalog' => $catalog,
            'portfolio' => $portfolio,
            'articles' => $articles,
            'reviews' => $reviews,
            'reviewListPage' => $reviewListPage,
            'interios' => $interios,
            'catalogMainPage' => $catalogMainPage,
        ]);
    }
}
