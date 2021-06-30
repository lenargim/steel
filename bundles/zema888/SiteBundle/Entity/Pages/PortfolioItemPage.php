<?php

namespace SiteBundle\Entity\Pages;

use SiteBundle\Admin\Pages\PortfolioItemPageAdmin;
use SiteBundle\Entity\Pages;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 */
class PortfolioItemPage extends Pages
{
    /**
     * Название Админского класса
     * @return string
     */
    public function getAdminClass(): string
    {
        return PortfolioItemPageAdmin::class;
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
    protected $image2;

    /**
     * картинка
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $image3;


    /**
     * На главную
     * @ORM\Column(type="boolean", nullable=true, options={"default":0})
     */
    protected $onmain;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $reviewId;

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
    public function getImage2()
    {
        return $this->image2;
    }

    /**
     * @param mixed $image2
     */
    public function setImage2($image2): void
    {
        $this->image2 = $image2;
    }

    /**
     * @return mixed
     */
    public function getImage3()
    {
        return $this->image3;
    }

    /**
     * @param mixed $image3
     */
    public function setImage3($image3): void
    {
        $this->image3 = $image3;
    }


    /**
     * @return mixed
     */
    public function getOnmain()
    {
        return $this->onmain;
    }

    /**
     * @param mixed $onmain
     */
    public function setOnmain($onmain): void
    {
        $this->onmain = $onmain;
    }

    /**
     * @return mixed
     */
    public function getReviewId()
    {
        return $this->reviewId;
    }

    /**
     * @param mixed $reviewId
     */
    public function setReviewId($reviewId): void
    {
        $this->reviewId = $reviewId;
    }

}
