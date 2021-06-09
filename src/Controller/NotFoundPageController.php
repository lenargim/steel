<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotFoundPageController extends AbstractController
{
  /**
  +      * @Route("/404")
  +      */
  public function not_found_page(): Response
  {
    return $this->render('404.html.twig', []);
  }
}