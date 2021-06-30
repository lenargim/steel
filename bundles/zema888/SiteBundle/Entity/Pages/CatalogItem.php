<?php
namespace SiteBundle\Entity\Pages;

use SiteBundle\Admin\Pages\CatalogItemAdmin;
use SiteBundle\Entity\Pages;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="SiteBundle\Repository\Pages\CatalogItemRepository")
 */
class CatalogItem extends Pages
{
    /**
     * Название Админского класса
     * @return string
     */
    public function getAdminClass(): string
    {
        return CatalogItemAdmin::class;
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
}
