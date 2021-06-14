<?php


namespace App\Services;


use App\Entity\City;
use Doctrine\ORM\EntityManagerInterface;
use SiteBundle\Entity\Pages\CatalogItem;

class UpdatePricesService
{
    protected EntityManagerInterface $em;

    /**
     * UpdatePrices constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function execute()
    {
        $products = $this->em->getRepository(CatalogItem::class)->findAll();
        $cities = $this->em->getRepository(City::class)->findAll();
        $defaultCity = $this->em->getRepository(City::class)->findOneBy(['bydefault' => true]);
        if (!$defaultCity) {
            throw  new \Exception('Не установлен город по умолчанию');
        }

        foreach ($products as $product) {
            $prices = $product->getPrices() ? $product->getPrices()[0] : [];
            $newPrices = [];
            foreach ($cities as $city) {
                if (empty($prices[$city->getId()])) {
                    $newPrices[$city->getId()] = $prices[$defaultCity->getId()] ?? '';
                } else {
                    $newPrices[$city->getId()] = $prices[$city->getId()];
                }
            }
            $product->setPrices([$newPrices]);
            $this->em->persist($product);
        }
        $this->em->flush();
        return count($products);
    }
}
