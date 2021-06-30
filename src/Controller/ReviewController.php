<?php

namespace App\Controller;

use App\Entity\Review;
use SiteBundle\Controller\BasePageController;
use SiteBundle\Entity\Pages;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends BasePageController
{

    public function list(Request $request)
    {
        $this->setPage($request);
        $reviews = $this->getDoctrine()->getManager()->getRepository(Review::class)->findBy([], ['position' => 'DESC']);
        return $this->render('review/list.html.twig', [
            'page' => $this->page,
            'reviews' => $reviews,
        ]);
    }
}
