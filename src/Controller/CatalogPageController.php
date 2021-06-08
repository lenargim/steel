<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CatalogPageController extends AbstractController
{
  /**
  +      * @Route("/catalog")
  +      */
  public function catalog(): Response
  {
    return $this->render('catalog/catalog.html.twig', []);
  }
}
