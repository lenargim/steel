<?php

namespace SiteBundle\Repository;

use Doctrine\DBAL\Types\TextType;
use SiteBundle\Entity\SiteStrings;
use SiteBundle\Helper\DataHandler;

/**
 * SiteStringsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SiteStringsRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Массив код-значение
     * @return array [code => value]
     */
    public function getList()
    {
        $objs = $this->findAll();
        $res = [];
        foreach ($objs as $obj) {
            $res[$obj->getAlias() . $obj->getNamespace()] = $obj->getValue();
        }
        return $res;
    }

    /**
     * @return array
     */
    public function getNamespaces()
    {
        $res = [];
        $objs = $this->findAll();
        foreach ($objs as $obj) {
            $res[$obj->getNamespace()] = $obj->getNamespace();
        }
        return $res;
    }


    /**
     * @param $ruText
     * @param string $group
     * @param string $type
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getString($ruText, $group = 'Общее', $type = TextType::class)
    {
        $alias = $this->getAliasByRutext($ruText);
        if (!SiteStrings::$all) {
            SiteStrings::$all = $this->getList();
        }
        if (!array_key_exists($alias.$group, SiteStrings::$all)) {
            $this->addString($ruText, $alias,  $group, $type);
        }
        return isset(SiteStrings::$all[$alias.$group]) ? SiteStrings::$all[$alias.$group] : '';
    }

    /**
     * Добавление текста автоматически
     *
     * @param $ruText
     * @param $alias
     * @param $group
     * @param $type
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function addString($ruText, $alias, $group, $type)
    {
        $obj = new SiteStrings();
        $obj->setTitle($ruText);
        $obj->setValue($ruText);
        $obj->setAlias($alias);
        $obj->setNamespace($group);
        $obj->setFieldType($type);
        $em = $this->getEntityManager();
        $em->persist($obj);
        $em->flush($obj);
        SiteStrings::$all[$alias.$group] = $ruText;
    }

    /**
     * @param $ruText
     * @return string
     */
    protected function getAliasByRutext($ruText)
    {
        return  DataHandler::urlTranslit(trim($ruText));
    }

    /**
     * Тексты для ЛК специалистов
     *
     * @return array
     */
    public function getLkTexts()
    {
        return array_map(function ($item) {
            return [
                'code' => $item->getAlias(),
                'value' => $item->getValue(),
            ];
        }, $this->findBy(['namespace' => 'ЛК специалиста']));
    }

    /**
     * Тексты для ЛК родителей
     *
     * @return array
     */
    public function getCabinetTexts()
    {
        return array_map(function ($item) {
            return [
                'code' => $item->getAlias(),
                'value' => $item->getValue(),
            ];
        }, $this->findBy(['namespace' => 'ЛК Родителя']));
    }
}
