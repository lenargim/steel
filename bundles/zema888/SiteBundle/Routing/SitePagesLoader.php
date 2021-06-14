<?php
namespace SiteBundle\Routing;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use SiteBundle\Entity\Pages;

class SitePagesLoader extends Loader
{
    private $loaded = false;
    /**
     *
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add this loader twice');
        }
        $routes = new RouteCollection();
        $pageRepo = $this->em->getRepository(Pages::class);
        // Пересчёт только если есть пустые роуты
        if ($pageRepo->checkEmptyRoute()) {
            $pageRepo->updateAllPathRoute(false);
        }

        $maxId = $pageRepo->getMaxId();
        for ($i = 0; $i <= $maxId; $i += 100) {
            $routeArray = $pageRepo->getRouteArray($i, 100);
            foreach ($routeArray as $page) {
                if ($page['controller'] && $page['path'] !== null && $page['route']) {
                    $pattern = $page['path'];
                    $defaults = array(
                        '_controller' => $page['controller'],
                    );
                    $route = new Route($pattern, $defaults);
                    $routes->add($page['route'], $route);
                }
            }
        }

        $this->loaded = true;
        return $routes;
    }

    public function supports($resource, $type = null)
    {
        return 'site_page' === $type;
    }
}
