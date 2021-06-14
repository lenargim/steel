<?php


namespace App\Import\Domain\Repository;


interface ItemRepositoryInterface
{
    /**
     * @return int
     */
    public function deleteAll(): int;
}