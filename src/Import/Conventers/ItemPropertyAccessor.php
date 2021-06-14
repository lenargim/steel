<?php


namespace App\Import\Conventers;


use App\Entity\City;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyAccess\Exception;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyAccess\PropertyPathInterface;

class ItemPropertyAccessor implements PropertyAccessorInterface
{
    protected EntityManagerInterface $em;
    protected array $citiesById = [];
    protected array $citiesByDomain = [];

    /**
     * UpdatePrices constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        foreach ($this->em->getRepository(City::class)->findAll() as $city) {
            $this->citiesById[$city->getId()] = $city->getDomain();
            $this->citiesByDomain[$city->getDomain()] = $city->getId();
        }
    }

    /**
     * @param array|object $objectOrArray
     * @param string|PropertyPathInterface $propertyPath
     * @param mixed $value
     */
    public function setValue(&$objectOrArray, $propertyPath, $value)
    {
        if ($propertyPath == 'prices') {
            $values = [];
            foreach ($value as $cityDomain => $price){

                if (!empty($this->citiesByDomain[$cityDomain])) {
                    $values[$this->citiesByDomain[$cityDomain]] = $this->preparePrice($price);
                }
            }
            $objectOrArray->setPrices($values);
        } else {
            $objectOrArray->{$this->setMethodName($propertyPath)}($value);
        }

    }

    /**
     * @param array|object $objectOrArray
     * @param string|PropertyPathInterface $propertyPath
     * @return array|mixed
     */
    public function getValue($objectOrArray, $propertyPath)
    {
        if ($propertyPath == 'prices') {
            $value = [];
            $prices = $objectOrArray->getPrices() ? $objectOrArray->getPrices()[0] : [];
            foreach ($prices as $cityId => $priceValue) {
                $value[$this->citiesById[$cityId]] = $priceValue;
            }
            return $value;
        }
        return $objectOrArray->{$this->getMethodName($propertyPath)}();
    }

    /**
     * @param array|object $objectOrArray
     * @param string|PropertyPathInterface $propertyPath
     * @return bool
     */
    public function isWritable($objectOrArray, $propertyPath)
    {
        return true;
    }

    /**
     * @param array|object $objectOrArray
     * @param string|PropertyPathInterface $propertyPath
     * @return bool
     */
    public function isReadable($objectOrArray, $propertyPath)
    {
        return true;
    }

    /**
     * @param $propertyPath
     * @return string
     */
    protected function getMethodName($propertyPath): string
    {
        return 'get' . ucfirst($propertyPath);
    }

    /**
     * @param $propertyPath
     * @return string
     */
    protected function setMethodName($propertyPath): string
    {
        return 'set' . ucfirst($propertyPath);
    }

    /**
     * @param string $price
     * @return int
     */
    protected function preparePrice(string $price): int
    {
        return intval(preg_replace(
            [
                '/\s/ui',
                '/,/ui',
            ],
            [
                '',
                '.'
            ],
            $price
        ));
    }

    /**
     * @param string $description
     * @return string | null
     */
    protected function prepareDescription(string $description): ?string
    {
        return $description == '-' ? null : $description;
    }
}
