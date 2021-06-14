<?php

namespace SiteBundle\Entity\Pages;

use SiteBundle\Admin\Pages\CatalogArticlePageAdmin;
use SiteBundle\Entity\Pages;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 */
class CatalogArticle extends Pages
{
    /**
     * Название Админского класса
     * @return string
     */
    public function getAdminClass(): string
    {
        return CatalogArticlePageAdmin::class;
    }

    /**
     * картинка
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $image;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $mainTitle;

    /**
     * Популярные
     * @ORM\Column(type="boolean", nullable=true, options={"default":0})
     */
    protected $popular;


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

    public function getPopular(): ?bool
    {
        return $this->popular;
    }

    public function setPopular(?bool $popular): self
    {
        $this->popular = $popular;

        return $this;
    }
}
