<?php

namespace App\Controller;

use SiteBundle\Controller\BasePageController;
use SiteBundle\Entity\Pages;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends BasePageController
{
    public function index(Request $request)
    {
        $this->setPage($request);
        $searchText = $request->get('search', '');
        $searchResults = $this->em->getRepository(Pages\CatalogItem::class)->searchText(trim($searchText), 1);
        $this->page->setH1($this->page->getH1() . ' "' . $searchText . '"');
        return $this->render('search/index.html.twig', [
            'page' => $this->page,
            'city' => $this->city,
            'searchText' => $searchText,
            'searchResults' => $searchResults,
        ]);
    }
}
