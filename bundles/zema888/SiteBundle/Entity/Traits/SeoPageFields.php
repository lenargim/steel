<?php
namespace SiteBundle\Entity\Traits;


use SiteBundle\Entity\Pages;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

trait SeoPageFields
{


    /**
     * @ORM\Column(type="integer", options={"default":0}, nullable=true)
     */
    protected $topMenu;
    /**
     * @ORM\Column(type="integer", options={"default":0}, nullable=true)
     */
    protected $bottomMenu;

    /**
     * @ORM\Column(type="string", length=128, unique=false)
     * @Assert\Length(
     *      min = 1,
     *      max = 128,
     *      maxMessage= "Псевдоним для URL введено неправильно. Максимум 128 символов",
     *      minMessage= "Псевдоним для URL введено неправильно. Минимум 3 символа"
     * )
     * @Assert\Regex(
     *     pattern= "/^([_a-z\d-]*|\/)$/ui",
     *     message= "Только маленькие англ буквы, цифры или тире"
     * )
     */
    protected $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $route;


    /**
     * @ORM\Column(type="boolean", options={"default" : 1})
     */
    protected $active;




    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $title;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $h1;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $menutitle;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $keywords;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $seotitle;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $text;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $announce;


    public function getTopMenu(): ?int
    {
        return $this->topMenu;
    }

    public function setTopMenu(?int $topMenu): self
    {
        $this->topMenu = $topMenu;

        return $this;
    }

    public function getBottomMenu(): ?int
    {
        return $this->bottomMenu;
    }

    public function setBottomMenu(?int $bottomMenu): self
    {
        $this->bottomMenu = $bottomMenu;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(?string $route): self
    {
        $this->route = $route;

        return $this;
    }


    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getH1(): ?string
    {
        return $this->h1;
    }

    public function setH1(?string $h1): self
    {
        $this->h1 = $h1;

        return $this;
    }

    public function getMenutitle(): ?string
    {
        return $this->menutitle;
    }

    public function setMenutitle(?string $menutitle): self
    {
        $this->menutitle = $menutitle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(?string $keywords): self
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function getSeotitle(): ?string
    {
        return $this->seotitle;
    }

    public function setSeotitle(?string $seotitle): self
    {
        $this->seotitle = $seotitle;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getAnnounce(): ?string
    {
        return $this->announce;
    }

    public function setAnnounce(?string $announce): self
    {
        $this->announce = $announce;

        return $this;
    }

}
