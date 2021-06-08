<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InteriorDetailedController extends AbstractController
{
  /**
  +      * @Route("/interior/interior-detailed")
  +      */
  public function interior_detailed(): Response
  {
    return $this->render('/interior/interior-detailed.html.twig', []);
  }
}
