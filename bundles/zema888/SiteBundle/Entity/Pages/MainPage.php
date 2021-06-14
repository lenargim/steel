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
     * Название Админского класса
     * @return string
     */
    public function getAdminClass(): string
    {
        return MainPageAdmin::class;
    }
}
