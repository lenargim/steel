<?php
namespace SiteBundle\Service;


use Doctrine\ORM\EntityManager;
use SiteBundle\Entity\Pages;
use SiteBundle\Helper\ArrayToXmlHelper;

class SitemapService
{
    /**
     * @var string
     */
    protected $hostName;

    /**
     *
     * @var EntityManager
     */
    protected $em;

    /**
     * SitemapService constructor.
     * @param string $hostName
     * @param EntityManager $em
     */
    public function __construct(string $hostName, EntityManager $em)
    {
        $this->hostName = $hostName;
        $this->em = $em;
    }

    public function execute(): int
    {
        $pages = $this->em->getRepository(Pages::class)->findBy(['active' => true], ['lvl'=>'ASC']);
        $sitemap = [];
        foreach ($pages as $page) {
            $sitemap[] = [
                'loc' => 'https://@HOST@' . ($page->getPath() === '/' ? '' : $page->getPath()),
                'lastmod' => $page->getUpdatedAt()->format('c'),
            ];
        }
        $xml_helper = ArrayToXmlHelper::createXML('urlset', [
            '@attributes' => [
                'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
                'xmlns' => 'http://www.sitemaps.org/schemas/sitemap/0.9',
                'xsi:schemaLocation' => 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd'

            ],
            'url' => $sitemap
        ]);
        file_put_contents(__DIR__.'/../../../../public/_sitemap.xml', $xml_helper->saveXML());
        return count($sitemap);
    }

}
