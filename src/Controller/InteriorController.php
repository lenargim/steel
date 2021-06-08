<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InteriorController extends AbstractController
{
  /**
  +      * @Route("/interior")
  +      */
  public function interior(): Response
  {
    return $this->render('/interior/interior.html.twig', []);
  }
}
