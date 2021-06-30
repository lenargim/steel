<?php
namespace SiteBundle\Entity\Pages;

use SiteBundle\Admin\Pages\ContactsPageAdmin;
use SiteBundle\Admin\Pages\ListAdmin;
use SiteBundle\Admin\Pages\ReviewsListPageAdmin;
use SiteBundle\Entity\Pages;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class ReviewsListPage extends Pages
{
    /**
     * Название Админского класса
     * @return string
     */
    public function getAdminClass(): string
    {
        return ReviewsListPageAdmin::class;
    }
}
