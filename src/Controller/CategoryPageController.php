<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryPageController extends AbstractController
{
  /**
  +      * @Route("/category")
  +      */
  public function category(): Response
  {
    return $this->render('catalog/category.html.twig', []);
  }
}
