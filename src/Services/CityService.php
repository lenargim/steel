<?php


namespace App\Services;


use App\Entity\City;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use SiteBundle\Entity\Pages\CatalogItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class CityService
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var KernelInterface
     */
    protected $appKernel;

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var EntityRepository
     */
    protected $cityRepo;

    /**
     * @var City
     */
    protected City $defaultCity;



    /**
     * CityService constructor.
     * @param EntityManagerInterface $em
     * @param KernelInterface $appKernel
     * @param SessionInterface $session
     */
    public function __construct(EntityManagerInterface $em, KernelInterface $appKernel, SessionInterface $session)
    {
        $this->em = $em;
        $this->appKernel = $appKernel;
        $this->session = $session;
        $this->init();
    }

    /**
     * @throws \Exception
     */
    protected function init() {
        $this->cityRepo = $this->em->getRepository(City::class);
        $defaultCity = $this->cityRepo->findOneBy(['bydefault' => 1]);
        if (!$defaultCity) {
            throw  new \Exception('Не установлен город по умолчанию');
        }
        $this->defaultCity = $defaultCity;
    }

    /**
     * @param Request $request
     * @return City | null
     * @throws \Exception
     */
    protected function getGeoCity(Request $request)
    {
        $path = $this->appKernel->getProjectDir() . '/GeoBase/IP2LOCATION-LITE-DB3.BIN/IP2LOCATION-LITE-DB3.BIN';
        $ip = $request->getClientIp();

        if ($request->get('debug_ip', false)) {
            $ip = $request->get('debug_ip', false);
        }

        $db = new \IP2Location\Database ($path, \IP2Location\Database::FILE_IO);

        $records = $db->lookup($ip, \IP2Location\Database::ALL);
        if ($records) {
            $city = $this->cityRepo->getCityByGeoCityName($records['regionName']);
            if ($city) {
                return $city;
            }
            $city = $this->cityRepo->getCityByGeoCityName($records['cityName']);
            if ($city) {
                return $city;
            }
        }
        return null;
    }

    /**
     * @param Request $request
     * @return City
     */
    public function getHostCity(Request $request): City
    {
        $host = $request->getHost();
        /** @var City $city */
        $city = $this->cityRepo
            ->findOneBy(['domain' => $host]);
        return $city ?: $this->defaultCity;
    }

    /**
     * @param $id
     */
    public function selectCityId($id)
    {
        $this->session->set('city_id', $id);
    }

    /**
     * проверяем надо ли опрашивать пользователя про город
     *
     * @param Request $request
     * @return City | null
     */
    public function askedUserCity(Request $request)
    {
        $domainCity = $this->getHostCity($request);
        $cookieCityId = $request->cookies->get('selectedCity', null);
        $sessionCityId = $this->session->get('city_id', null);


        // Если записано в сессию текущий город то всё ок
        if ($domainCity->getId() == $sessionCityId) {
            return null;
        }

        // на случай закрытия окна вопроса без выбора, значит хочет текущий
        $this->selectCityId($domainCity->getId());

        if ($cookieCityId) {
            // Если была установлена кука на текущий город, то всё ок
            if ($domainCity->getId() == $cookieCityId) {
                return null;
            } else {
                //Если город по куке не совпадает с текущим -надо спросить
                return $this->cityRepo
                    ->find($cookieCityId);
            }
        }

        $geoCity = $this->getGeoCity($request);

        // Если город по гео не нашелся в нашей базе, то пусть смотрит туда куда зашел
        if (!$geoCity || $geoCity->getId() == $domainCity->getId()) {
            return null;
        } else {
            //Если город по гео не совпадает с текущим и до того нетв сессии и куках, то спрашиваем
            return $geoCity;
        }
    }

    /**
     * @param CatalogItem $item
     * @param Request $request
     * @return string|null
     */
    public function getPrice(CatalogItem $item, Request $request): ?string
    {
        $city = $this->getHostCity($request);
        if ($city && !empty($item->getPrices())) {
            $prices = $item->getPrices()[0];
            if (empty($prices[$city->getId()])) {
                return $prices[$this->defaultCity->getId()] ?? null;
            }
            return $prices[$city->getId()];
        }
        return null;
    }
}
