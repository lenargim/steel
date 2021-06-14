<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use SiteBundle\Entity\Pages;
use SiteBundle\Entity\Redirects;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RedirectController extends AbstractController
{
    /** @var  EntityManager */
    protected $em;

    /**
     * 404
     * @param $path
     * @return bool|\Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function pageNotFound($path)
    {
        $this->em = $this->getDoctrine()->getManager();
        if ($redirect = $this->Redirect301($path)) {
            return $redirect;
        }
        $page = new Pages();
        $page->setTitle('Страница не найдена');
        $page->setKeywords('Страница не найдена');
        $page->setDescription('Страница не найдена');
        $page->setRoute('zema_pages');
        return $this->render('redirect/404.html.twig', [
            'page' => $page
        ], new Response("", 404));
    }

    /**
     * 301 редирект
     * @param $path
     * @return bool|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function Redirect301($path)
    {
        $redirect = $this->em->getRepository(Redirects::class)->findOneBy(['redirectfrom' => $path]);
        if ($redirect) {
            if (preg_match("#^http#", $redirect->getRedirectto(), $match)) {
                return $this->redirect($redirect->getRedirectto(), 301);
            }
            return $this->redirect($redirect->getRedirectto(), 301);
        }
        return false;
    }
}
