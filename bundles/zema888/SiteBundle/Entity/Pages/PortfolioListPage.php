<?php
namespace SiteBundle\Entity\Pages;

use SiteBundle\Admin\Pages\ContactsPageAdmin;
use SiteBundle\Admin\Pages\ListAdmin;
use SiteBundle\Entity\Pages;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class PortfolioListPage extends Pages
{
    /**
     * Название Админского класса
     * @return string
     */
    public function getAdminClass(): string
    {
        return ListAdmin::class;
    }
}
