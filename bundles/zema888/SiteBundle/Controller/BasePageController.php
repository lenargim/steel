<?php

namespace SiteBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use SiteBundle\Entity\Pages;
use SiteBundle\Repository\PagesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BasePageController extends AbstractController
{
    protected EntityManagerInterface $em;

    protected PagesRepository $pageRepo;
    protected Pages $page;

    /**
     * BasePageController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * Поиск текущей страницы по роуту
     * @param Request $request
     */
    protected function setPage(Request $request)
    {
        $this->pageRepo = $this->em->getRepository(Pages::class);
        $this->page = $this->pageRepo->findOneBy(['route' => $request->get('_route')]);
    }
}
