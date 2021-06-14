<?php

namespace SiteBundle\Controller;

use App\Entity\City;
use App\Services\CityService;
use Doctrine\ORM\EntityManagerInterface;
use SiteBundle\Entity\Pages;
use SiteBundle\Repository\PagesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BasePageController extends AbstractController
{
    protected EntityManagerInterface $em;
    protected CityService $cityService;

    protected PagesRepository $pageRepo;
    protected Pages $page;
    protected City $city;

    /**
     * BasePageController constructor.
     * @param EntityManagerInterface $em
     * @param CityService $cityService
     */
    public function __construct(EntityManagerInterface $em, CityService $cityService)
    {
        $this->em = $em;
        $this->cityService = $cityService;
    }


    /**
     * Поиск текущей страницы по роуту
     * @param Request $request
     */
    protected function setPage(Request $request)
    {
        $this->city = $this->cityService->getHostCity($request);
        $this->pageRepo = $this->em->getRepository(Pages::class);
        $this->page = $this->pageRepo->findOneBy(['route' => $request->get('_route')]);
    }
}
