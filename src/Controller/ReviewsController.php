<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewsController extends AbstractController
{
  /**
  +      * @Route("/reviews")
  +      */
  public function reviews(): Response
  {
    return $this->render('reviews.html.twig', []);
  }
}