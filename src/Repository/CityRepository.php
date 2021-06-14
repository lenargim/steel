<?php


namespace App\Repository;

use App\Entity\City;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

class CityRepository extends EntityRepository
{
    /**
     * @param $cityName
     * @return int|mixed|string|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getCityByGeoCityName($cityName)
    {
        $em = $this->getEntityManager();
        $tableName = $em->getClassMetadata(City::class)->getTableName();
        $sql = "SELECT *
                FROM {$tableName} table_alias
                WHERE `geo_city_name` RLIKE :CITYNAME
               ";
        $rsm = new ResultSetMappingBuilder($em);
        $rsm->addRootEntityFromClassMetadata(City::class, 'alias');
        $selectClause = $rsm->generateSelectClause([ 'alias' => 'table_alias' ]);
        $query = $em->createNativeQuery($sql, $rsm);
//        $query->setParameters([':CITYNAME' => '%' . $cityName . '%']);
        $query->setParameters([':CITYNAME' => $cityName]);
        return $query->getOneOrNullResult();
    }
}
