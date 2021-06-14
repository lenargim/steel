<?php
namespace App\Import\Conventers;

use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class ItemNameConverter implements NameConverterInterface
{

    protected array $normalizeName = [
        "id" => "id",
        "menutitle" => "Наименование",
        "prices" => "Цена",
        "importId" => "ID",
    ];

    protected array $denormalizeNames = [
        "ID" => "id",
        "Цена" => "prices",
        "Наименование" => "menutitle",
    ];

    public function normalize($propertyName)
    {
        return $this->normalizeName[$propertyName] ?? $propertyName;
    }

    public function denormalize($propertyName)
    {
        return $this->denormalizeNames[$propertyName] ?? $propertyName;
    }

}
