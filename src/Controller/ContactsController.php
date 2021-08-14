<?php

namespace App\Controller;

use SiteBundle\Controller\BasePageController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactsController extends BasePageController
{
    public function index(Request $request): Response
    {
        $this->setPage($request);
        return $this->render('contacts/index.html.twig', [
            'page' => $this->page,
        ]);
    }
}
