<?php

namespace App\Controller;

use SiteBundle\Controller\BasePageController;
use SiteBundle\Entity\Pages;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TextController extends BasePageController
{
    public function index(Request $request)
    {
        $this->setPage($request);
        return $this->render('text/index.html.twig', [
            'page' => $this->page,
        ]);
    }
}
