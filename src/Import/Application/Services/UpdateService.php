<?php


namespace App\Import\Application\Services;


use App\Import\Domain\Entity\Item;
use App\Import\Domain\Exceptions\ImportException;
use Doctrine\ORM\EntityManagerInterface;
use SiteBundle\Entity\Pages\CatalogArticle;
use SiteBundle\Entity\Pages\CatalogItem;

class UpdateService
{
    protected EntityManagerInterface $em;

    /**
     * UpdateService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    public function execute(
        CatalogArticle $category,
        array $importItems
    ): int
    {
        $count = 0;
        $items = $category->getChildren([CatalogItem::class]);
        try {
            $this->em->getConnection()->beginTransaction();
            /** @var Item $importItem */
            foreach ($importItems as $importItem) {
                foreach ($items as $item) {
                    if ($item->getId() == $importItem->getId()) {

                        $item->setPrices(
                            [$importItem->getPrices()]
                        );
                        $this->em->persist($item);
                        $count++;
                    }
                }
            }
            $this->em->flush();
            $this->em->getConnection()->commit();
        } catch (\Throwable $e) {
            $this->em->getConnection()->rollBack();
            echo 'Ошибка' . $e->getMessage() . PHP_EOL;
            echo 'Откат изменений' . PHP_EOL;
            $count = 0;
        }

        return $count;
    }
}
