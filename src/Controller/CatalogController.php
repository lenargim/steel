<?php

namespace App\Controller;

use App\Form\MessageMail;
use App\Form\MessageMailType;
use SiteBundle\Controller\BasePageController;
use SiteBundle\Entity\Pages;
use Symfony\Component\HttpFoundation\Request;

class CatalogController extends BasePageController
{
    public function index(Request $request)
    {
        $this->setPage($request);
        /** @var Pages\CatalogItem $favorites */
        $favorites = $this->page->getArr1() ? $this->pageRepo->findBy(['id' => $this->page->getArr1()]) : [];
        $mailObj = new MessageMail();
        $mailObj->setTypeform('Узнать стоимость');
        $mailObj->setUrl($request->getSchemeAndHttpHost() . $this->get('router')->generate($this->page->getRoute()));
        $form = $this->createForm(MessageMailType::class, $mailObj)->createView();
        return $this->render('catalog/index.html.twig', [
            'page' => $this->page,
            'favorites' => $favorites,
            'form' => $form,
        ]);
    }


    public function list(Request $request)
    {
        $this->setPage($request);
        return $this->render('catalog/list.html.twig', [
            'page' => $this->page,
        ]);
    }

    public function main(Request $request)
    {
        $this->setPage($request);
        return $this->render('catalog/main.html.twig', [
            'page' => $this->page,
        ]);
    }

}
