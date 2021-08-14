<?php
namespace SiteBundle\Entity\Pages;

use SiteBundle\Admin\Pages\MainPageAdmin;
use SiteBundle\Entity\Pages;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class MainPage extends Pages
{

    /**
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $subTitle;

    /**
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $linkTitle;

    /**
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $link;


    /**
     * Название Админского класса
     * @return string
     */
    public function getAdminClass(): string
    {
        return MainPageAdmin::class;
    }

    /**
     * @return mixed
     */
    public function getSubTitle()
    {
        return $this->subTitle;
    }

    /**
     * @param mixed $subTitle
     */
    public function setSubTitle($subTitle): void
    {
        $this->subTitle = $subTitle;
    }

    /**
     * @return mixed
     */
    public function getLinkTitle()
    {
        return $this->linkTitle;
    }

    /**
     * @param mixed $linkTitle
     */
    public function setLinkTitle($linkTitle): void
    {
        $this->linkTitle = $linkTitle;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param mixed $link
     */
    public function setLink($link): void
    {
        $this->link = $link;
    }



}
