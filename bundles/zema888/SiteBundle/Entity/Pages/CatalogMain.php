<?php
namespace SiteBundle\Entity\Pages;

use SiteBundle\Admin\Pages\CatalogMainAdmin;
use SiteBundle\Admin\Pages\ListAdmin;
use SiteBundle\Admin\Pages\MainPageAdmin;
use SiteBundle\Entity\Pages;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class CatalogMain extends Pages
{
    /**
     * Название Админского класса
     * @return string
     */
    public function getAdminClass(): string
    {
        return CatalogMainAdmin::class;
    }

}
