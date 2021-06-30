<?php

namespace SiteBundle\Entity\Pages;

use SiteBundle\Admin\Pages\InteriorItemPageAdmin;
use SiteBundle\Admin\Pages\ServicePageAdmin;
use SiteBundle\Entity\Pages;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 */
class InteriorItemPage extends Pages
{
    /**
     * Название Админского класса
     * @return string
     */
    public function getAdminClass(): string
    {
        return InteriorItemPageAdmin::class;
    }

    /**
     * картинка
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $image;

    /**
     * картинка
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $image1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $company;

    /**
     * @ORM\Column(type="date", length=255, nullable=true)
     */
    protected $year;

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image): void
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     */
    public function setCompany($company): void
    {
        $this->company = $company;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param mixed $year
     */
    public function setYear($year): void
    {
        $this->year = $year;
    }

    /**
     * @return mixed
     */
    public function getImage1()
    {
        return $this->image1;
    }

    /**
     * @param mixed $image1
     */
    public function setImage1($image1): void
    {
        $this->image1 = $image1;
    }


}
