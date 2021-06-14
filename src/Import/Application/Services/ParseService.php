<?php

namespace App\Import\Application\Services;


use App\Import\Conventers\ItemNameConverter;
use App\Import\Conventers\ItemPropertyAccessor;
use App\Import\Domain\Entity\Item;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ParseService
{
    protected EntityManagerInterface $em;
    protected ItemNameConverter $nameConverter;
    protected ItemPropertyAccessor $accessor;

    /**
     * ParseService constructor.
     * @param EntityManagerInterface $em
     * @param ItemNameConverter $nameConverter
     * @param ItemPropertyAccessor $accessor
     */
    public function __construct(EntityManagerInterface $em, ItemNameConverter $nameConverter, ItemPropertyAccessor $accessor)
    {
        $this->em = $em;
        $this->nameConverter = $nameConverter;
        $this->accessor = $accessor;
    }


    /**
     * @return Serializer
     */
    protected function getSerializer(): Serializer
    {

        $encoders = [new CsvEncoder()];
        $normalizer = new ObjectNormalizer(null, $this->nameConverter, $this->accessor);
        return new Serializer([$normalizer, new ArrayDenormalizer()], $encoders);
    }

    /**
     * @param $importFilePath
     * @return Item[]
     */
    public function deserialize($importFilePath): array
    {
        $serializer = $this->getSerializer();
        $context = [
            CsvEncoder::DELIMITER_KEY => ';',
            CsvEncoder::ENCLOSURE_KEY => '"',
            CsvEncoder::ESCAPE_CHAR_KEY => '\\',
            CsvEncoder::KEY_SEPARATOR_KEY => ';',
        ];
        $strData = mb_convert_encoding(file_get_contents($importFilePath), "utf-8", "windows-1251");
        $itemClass = Item::class . '[]';
        return $serializer->deserialize($strData, $itemClass, 'csv', $context);
    }

    /**
     * @param $data
     * @return string
     */
    public function serialize($data):string
    {
        $serializer = $this->getSerializer();
        $context = [
            CsvEncoder::DELIMITER_KEY => ';',
            CsvEncoder::ENCLOSURE_KEY => '"',
            CsvEncoder::ESCAPE_CHAR_KEY => '\\',
            CsvEncoder::KEY_SEPARATOR_KEY => ';',
            AbstractNormalizer::ATTRIBUTES => [
                'menutitle',
                'importId',
                'prices',
            ]
        ];
        $csv = $serializer->serialize(
            $data,
            'csv',
            $context
        );
        return mb_convert_encoding($csv, "windows-1251", "utf-8");
    }
}
