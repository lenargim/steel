<?php

namespace App\Controller;

use SiteBundle\Controller\BasePageController;
use SiteBundle\Entity\Pages;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InteriorController extends BasePageController
{
    public function index(Request $request)
    {
        $this->setPage($request);
        return $this->render('interior/index.html.twig', [
            'page' => $this->page,
        ]);
    }

    public function list(Request $request)
    {
        $this->setPage($request);
        return $this->render('interior/list.html.twig', [
            'page' => $this->page,
        ]);
    }
}
