<?php
namespace App\Import\Domain\Entity;


class Item
{
    protected ?int $id = null;

    protected array $prices = [];


    /**
     * @return bool
     */
    public function isFulled(): bool
    {
        return true;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function getPrices(): array
    {
        return $this->prices;
    }

    /**
     * @param array $prices
     */
    public function setPrices(array $prices): void
    {
        $this->prices = $prices;
    }

    /**
     * @param string|null $menutitle
     */
    public function setMenutitle(?string $menutitle): void
    {

    }
}
