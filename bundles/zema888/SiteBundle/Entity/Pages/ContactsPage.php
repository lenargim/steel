<?php
namespace SiteBundle\Entity\Pages;

use SiteBundle\Admin\Pages\ContactsPageAdmin;
use SiteBundle\Admin\Pages\ListAdmin;
use SiteBundle\Admin\Pages\MainPageAdmin;
use SiteBundle\Entity\Pages;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class ContactsPage extends Pages
{


    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $mapLat;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $mapLng;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $mapPhone;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $mapEmail;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $mapAddress;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $mapKey;

    /**
     * Название Админского класса
     * @return string
     */
    public function getAdminClass(): string
    {
        return ContactsPageAdmin::class;
    }

    /**
     * @return mixed
     */
    public function getMapLat()
    {
        return $this->mapLat;
    }

    /**
     * @param mixed $mapLat
     */
    public function setMapLat($mapLat): void
    {
        $this->mapLat = $mapLat;
    }

    /**
     * @return mixed
     */
    public function getMapLng()
    {
        return $this->mapLng;
    }

    /**
     * @param mixed $mapLng
     */
    public function setMapLng($mapLng): void
    {
        $this->mapLng = $mapLng;
    }

    /**
     * @return mixed
     */
    public function getMapPhone()
    {
        return $this->mapPhone;
    }

    /**
     * @param mixed $mapPhone
     */
    public function setMapPhone($mapPhone): void
    {
        $this->mapPhone = $mapPhone;
    }

    /**
     * @return mixed
     */
    public function getMapEmail()
    {
        return $this->mapEmail;
    }

    /**
     * @param mixed $mapEmail
     */
    public function setMapEmail($mapEmail): void
    {
        $this->mapEmail = $mapEmail;
    }

    /**
     * @return mixed
     */
    public function getMapAddress()
    {
        return $this->mapAddress;
    }

    /**
     * @param mixed $mapAddress
     */
    public function setMapAddress($mapAddress): void
    {
        $this->mapAddress = $mapAddress;
    }

    /**
     * @return mixed
     */
    public function getMapKey()
    {
        return $this->mapKey;
    }

    /**
     * @param mixed $mapKey
     */
    public function setMapKey($mapKey): void
    {
        $this->mapKey = $mapKey;
    }
}
