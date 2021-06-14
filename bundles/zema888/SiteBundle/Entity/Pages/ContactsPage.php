<?php

namespace SiteBundle\Entity\Pages;

use Doctrine\ORM\Mapping as ORM;
use SiteBundle\Admin\Pages\CatalogArticlePageAdmin;
use SiteBundle\Admin\Pages\ContactsPageAdmin;
use SiteBundle\Entity\Pages;

/**
 * @ORM\Entity()
 */
class ContactsPage extends Pages
{

    /**
     * Название Админского класса
     * @return string
     */
    public function getAdminClass(): string
    {
        return ContactsPageAdmin::class;
    }

}
