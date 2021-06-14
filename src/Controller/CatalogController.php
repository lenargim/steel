<?php

namespace App\Controller;

use SiteBundle\Controller\BasePageController;
use SiteBundle\Entity\Module;
use SiteBundle\Entity\Pages;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class CatalogController extends BasePageController
{
    public function index(Request $request)
    {
        $this->setPage($request);
        /** @var Pages\CatalogItem $favorites */
        $favorites = $this->page->getFavorite() ? $this->pageRepo->findBy(['id' => $this->page->getFavorite()]) : [];
        return $this->render('catalog/index.html.twig', [
            'page' => $this->page,
            'city' => $this->city,
            'favorites' => $favorites,
        ]);
    }


    public function list(Request $request)
    {
        $this->setPage($request);
        $items = $this->page->getChildren([Pages\CatalogItem::class]);
        $template = count($items) > 0 ? 'catalog/list-items.html.twig' : 'catalog/list.html.twig';
        return $this->render($template, [
            'page' => $this->page,
            'city' => $this->city,
        ]);
    }
    public function main(Request $request)
    {
        $this->setPage($request);
        return $this->render('catalog/main.html.twig', [
            'page' => $this->page,
            'city' => $this->city,
        ]);
    }

}
