<?php
namespace SiteBundle\Repository\Pages;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use SiteBundle\Entity\Pages;

class CatalogItemRepository extends EntityRepository
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

    public function searchText($text, $page)
    {
        $limit   = 500;
        $textArr = explode(' ', preg_replace("/\s+/ui", ' ', $text));
        $result = [];
        if (!$text) {
            return $result;
        }
        if (mb_strlen($text) > 3 && array_reduce($textArr, function ($maxLen, $word){
                return mb_strlen($word) > $maxLen ? mb_strlen($word) : $maxLen;
            }, 0) > 3) {

            $words = [];

            foreach ($textArr as $key => $val) {
                if (mb_strlen($val) > 3) {
                    $words[] = $val;
                }
            }

            $words = implode(' ', $words);
            $query = $this->createQueryBuilder('p')
                ->add('where', 'MATCH_AGAINST(p.h1, p.menutitle, p.text, :searchterm) > 0')
                ->setParameter('searchterm', $words)
                ->orderBy("MATCH_AGAINST (p.h1, p.menutitle, p.text, :searchterm 'IN BOOLEAN MODE')", 'desc')
//                ->setMaxResults('100')
                ->getQuery();
        }
        if (!$result) {
            $text = '%' . preg_replace("/\s+/ui", '%', $text) . '%';
            $query = $this->createQueryBuilder('p')
                ->add('where', 'p.menutitle LIKE :searchterm')
                ->setParameter('searchterm', $text)
//                ->setMaxResults('100')
                ->getQuery();
        }
        $paginator = new Paginator($query, (int)$page);

        $result = [
            'count' => ceil($paginator->count() / $limit),
            'showPag' => $paginator->count() > $limit,
            'current' => (int)$page
        ];
        $paginator->getQuery()
            ->setFirstResult($limit * ((int)$page - 1))
            ->setMaxResults($limit);
        $result['items'] = $paginator->getQuery()->getResult();

        return $result;
    }

    /**
     * @param Pages $parent
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countRecursiveByParent(Pages $parent): int
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->where('p.lft >= :lft')
            ->andWhere('p.rgt <= :rgt')
            ->setParameters([
                'lft' => $parent->getLft(),
                'rgt' => $parent->getRgt(),
            ])
            ->getQuery()
            ->enableResultCache(null, sprintf('count_recursive_child_%s', $parent->getId()))
            ->getSingleScalarResult();
    }
}
