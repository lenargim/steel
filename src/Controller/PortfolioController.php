<?php

namespace App\Controller;

use App\Entity\Review;
use SiteBundle\Controller\BasePageController;
use SiteBundle\Entity\Pages;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PortfolioController extends BasePageController
{
    public function index(Request $request)
    {
        $this->setPage($request);
        $review = $this->page->getReviewId() ? $this->getDoctrine()->getManager()->getRepository(Review::class)->find($this->page->getReviewId()) : null;
        return $this->render('portfolio/index.html.twig', [
            'page' => $this->page,
            'review' => $review,
        ]);
    }

    public function list(Request $request)
    {
        $this->setPage($request);
        return $this->render('portfolio/list.html.twig', [
            'page' => $this->page,
        ]);
    }
}
