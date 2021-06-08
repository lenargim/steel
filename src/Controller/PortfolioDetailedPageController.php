<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PortfolioDetailedPageController extends AbstractController
{
  /**
  +      * @Route("/portfolio/portfolio-detailed")
  +      */
  public function portfolio_detailed(): Response
  {
    return $this->render('portfolio/portfolio-detailed.html.twig', []);
  }
}