<?php

namespace SiteBundle\Entity\Pages;

use SiteBundle\Admin\Pages\ServicePageAdmin;
use SiteBundle\Entity\Pages;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 */
class ServiceItem extends Pages
{
    /**
     * Название Админского класса
     * @return string
     */
    public function getAdminClass(): string
    {
        return ServicePageAdmin::class;
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
    protected $mainImage;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $mainTitle;

    /**
     * На главную
     * @ORM\Column(type="boolean", nullable=true, options={"default":0})
     */
    protected $onmain;


    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getMainTitle(): ?string
    {
        return $this->mainTitle;
    }

    public function setMainTitle(?string $mainTitle): self
    {
        $this->mainTitle = $mainTitle;

        return $this;
    }

    public function getMainImage(): ?string
    {
        return $this->mainImage;
    }

    public function setMainImage(?string $mainImage): self
    {
        $this->mainImage = $mainImage;

        return $this;
    }

    public function getOnmain(): ?bool
    {
        return $this->onmain;
    }

    public function setOnmain(?bool $onmain): self
    {
        $this->onmain = $onmain;

        return $this;
    }
}
