<?php
namespace SiteBundle\Repository;


use SiteBundle\Entity\PageBlocks;
use Doctrine\ORM\EntityRepository;

class PageBlocksRepository extends EntityRepository
{
    /**
     * Возвращает массив типблока => MainBlocks
     * @return array
     */
    public function getBlocks()
    {
        $blocksCollection = $this->findAll();
        $blocks = [];
        /** @var PageBlocks $block */
        foreach ($blocksCollection as $block) {
            $blocks[$block->getType()] = $block;
        }
        return $blocks;
    }
}