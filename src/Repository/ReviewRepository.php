<?php


namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

class ReviewRepository extends EntityRepository
{
    /**
     * Массив для choice в админке
     * @return array
     */
    public function getListByModule()
    {
        $collection = $this->findBy([]);
        $choices = [];
        foreach ($collection as $item) {
            $choices[$item->getName()] = $item->getId();
        }
        return $choices;
    }
}
