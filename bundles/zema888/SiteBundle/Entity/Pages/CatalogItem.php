<?php
namespace SiteBundle\Entity\Pages;

use SiteBundle\Admin\Pages\CatalogItemPageAdmin;
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
        return CatalogItemPageAdmin::class;
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
    protected $originalImage;

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
     *  Цена
     * @ORM\Column(type="integer", nullable=true, options={"default":0})
     */
    protected $price;


    /**
     * @ORM\Column(type="integer", nullable=true, options={"default":0})
     */
    protected $oldprice;


    /**
     * На главную
     * @ORM\Column(type="boolean", nullable=true, options={"default":0})
     */
    protected $onmain;

    /**
     * Популярные
     * @ORM\Column(type="boolean", nullable=true, options={"default":0})
     */
    protected $popular;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $descriptionText;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $specification;

    /**
     * Связанные товары
     * @ORM\Column(type="json_array", nullable=true)
     */
    protected $favorite;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    protected $prices = [];



    protected $cartCount;

    public function getMenutitle(): ?string
    {
        return parent::getMenutitle();
    }

    /**
     * @return int
     */
    public function getImportId(): int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCartCount()
    {
        return $this->cartCount;
    }

    /**
     * @param mixed $cartCount
     */
    public function setCartCount($cartCount): void
    {
        $this->cartCount = $cartCount;
    }




    public function getDescriptionText(): ?string
    {
        return $this->descriptionText;
    }

    public function setDescriptionText(?string $descriptionText): self
    {
        $this->descriptionText = $descriptionText;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

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

    public function getMainTitle(): ?string
    {
        return $this->mainTitle;
    }

    public function setMainTitle(?string $mainTitle): self
    {
        $this->mainTitle = $mainTitle;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getOldprice(): ?int
    {
        return $this->oldprice;
    }

    public function setOldprice(?int $oldprice): self
    {
        $this->oldprice = $oldprice;

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

    public function getSpecification(): ?string
    {
        return $this->specification;
    }

    public function setSpecification(?string $specification): self
    {
        $this->specification = $specification;

        return $this;
    }

    public function getFavorite()
    {
        foreach ($this->favorite as &$favorite) {
            if ($favorite < 1000) {
                $favorite += 1000;
            }
        }
        return $this->favorite;
    }

    public function setFavorite($favorite): self
    {
        $this->favorite = $favorite;

        return $this;
    }

    public function getOriginalImage(): ?string
    {
        return $this->originalImage;
    }

    public function setOriginalImage(?string $originalImage): self
    {
        $this->originalImage = $originalImage;

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

    /**
     * @return mixed
     */
    public function getPrices()
    {
        return $this->prices;
    }

    /**
     * @param mixed $prices
     */
    public function setPrices($prices): void
    {
        $this->prices = $prices;
    }

}
