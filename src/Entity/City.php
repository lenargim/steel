<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="city")
 * @ORM\Entity(repositoryClass="App\Repository\CityRepository")
 */
class City
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $alias;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @ORM\OrderBy({"title" = "ASC"})
     */
    protected $title;

    /**
     * кого-чего?
     * @ORM\Column(type="string", length=255, nullable=true)
     * @ORM\OrderBy({"title" = "ASC"})
     */
    protected $titleGen;

    /**
     * кого-что?
     * @ORM\Column(type="string", length=255, nullable=true)
     * @ORM\OrderBy({"title" = "ASC"})
     */
    protected $titleAccu;
    /**
     * Где, о ком ,чем
     * @ORM\Column(type="string", length=255, nullable=true)
     * @ORM\OrderBy({"title" = "ASC"})
     */
    protected $titlePrepos;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $phone2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $address;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $workTime;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $domain;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $bydefault;

    /**
    * @ORM\Column(type="json_array", nullable=true)
    */
    protected $points;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $col1;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $col2;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $headmetrika;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $yandexmetrika;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $geoCityName;

    /** @var City */
    public static $currentCity;

    public function __toString()
    {
        return $this->title ?: 'Создать город';
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param mixed $alias
     */
    public function setAlias($alias): void
    {
        $this->alias = $alias;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitleGen()
    {
        return $this->titleGen;
    }

    /**
     * @param mixed $titleGen
     */
    public function setTitleGen($titleGen): void
    {
        $this->titleGen = $titleGen;
    }

    /**
     * @return mixed
     */
    public function getTitleAccu()
    {
        return $this->titleAccu;
    }

    /**
     * @param mixed $titleAccu
     */
    public function setTitleAccu($titleAccu): void
    {
        $this->titleAccu = $titleAccu;
    }

    /**
     * @return mixed
     */
    public function getTitlePrepos()
    {
        return $this->titlePrepos;
    }

    /**
     * @param mixed $titlePrepos
     */
    public function setTitlePrepos($titlePrepos): void
    {
        $this->titlePrepos = $titlePrepos;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getPhone2()
    {
        return $this->phone2;
    }

    /**
     * @param mixed $phone2
     */
    public function setPhone2($phone2): void
    {
        $this->phone2 = $phone2;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address): void
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param mixed $domain
     */
    public function setDomain($domain): void
    {
        $this->domain = $domain;
    }

    /**
     * @return mixed
     */
    public function getBydefault()
    {
        return $this->bydefault;
    }

    /**
     * @param mixed $bydefault
     */
    public function setBydefault($bydefault): void
    {
        $this->bydefault = $bydefault;
    }

    /**
     * @return mixed
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @param mixed $points
     */
    public function setPoints($points): void
    {
        $this->points = $points;
    }

    /**
     * @return mixed
     */
    public function getCol1()
    {
        return $this->col1;
    }

    /**
     * @param mixed $col1
     */
    public function setCol1($col1): void
    {
        $this->col1 = $col1;
    }

    /**
     * @return mixed
     */
    public function getCol2()
    {
        return $this->col2;
    }

    /**
     * @param mixed $col2
     */
    public function setCol2($col2): void
    {
        $this->col2 = $col2;
    }

    /**
     * @return mixed
     */
    public function getHeadmetrika()
    {
        return $this->headmetrika;
    }

    /**
     * @param mixed $headmetrika
     */
    public function setHeadmetrika($headmetrika): void
    {
        $this->headmetrika = $headmetrika;
    }

    /**
     * @return mixed
     */
    public function getYandexmetrika()
    {
        return $this->yandexmetrika;
    }

    /**
     * @param mixed $yandexmetrika
     */
    public function setYandexmetrika($yandexmetrika): void
    {
        $this->yandexmetrika = $yandexmetrika;
    }

    /**
     * @return mixed
     */
    public function getGeoCityName()
    {
        return $this->geoCityName;
    }

    /**
     * @param mixed $geoCityName
     */
    public function setGeoCityName($geoCityName): void
    {
        $this->geoCityName = $geoCityName;
    }

    /**
     * @return City
     */
    public static function getCurrentCity(): City
    {
        return self::$currentCity;
    }

    /**
     * @param City $currentCity
     */
    public static function setCurrentCity(City $currentCity): void
    {
        self::$currentCity = $currentCity;
    }

    /**
     * @return mixed
     */
    public function getWorkTime()
    {
        return $this->workTime;
    }

    /**
     * @param mixed $workTime
     */
    public function setWorkTime($workTime): void
    {
        $this->workTime = $workTime;
    }

}
