<?php

namespace SiteBundle\Command;

use SiteBundle\Service\SitemapService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SitemapCommand extends Command
{
    /**
     * @var SitemapService
     */
    private $sitemapService;

    /**
     * SitemapCommand constructor.
     * @param SitemapService $sitemapService
     */
    public function __construct(SitemapService $sitemapService)
    {
        $this->sitemapService = $sitemapService;
        parent::__construct();
    }


    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('site:sitemap:update')
            ->setDescription('Создание/обновление карты сайта');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pages = $this->sitemapService->execute();
        $output->writeln('sitemap.xml содержит ' . $pages . ' страниц.');
    }
}
