<?php

namespace SiteBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use SiteBundle\Entity\Pages;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SitePagesActivateAllCommand extends Command
{

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * SitePagesPathCommand constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }


    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('site:site_pages_activate_all')
            ->setDescription('Активировать все страницы');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em->getRepository(Pages::class)->activateAll();
        $output->writeln('Активированны все страницы');
    }
}
