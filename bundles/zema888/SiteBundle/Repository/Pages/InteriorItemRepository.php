<?php
namespace SiteBundle\Repository\Pages;


use Doctrine\ORM\EntityRepository;

class InteriorItemRepository extends EntityRepository
{
    /**
     * Массив для choice в админке
     * @return array
     */
    public function getListByModule()
    {
        $collection = $this->findBy([], ['lft' => 'ASC']);
        $choices = [];
        foreach ($collection as $item) {
            $choices[$item->getMenutitle()] = $item->getId();
        }
        return $choices;
    }
}
