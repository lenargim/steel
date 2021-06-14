<?php

namespace SiteBundle\Command;

use Doctrine\ORM\EntityManager;
use SiteBundle\Entity\Pages;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SitePagesPathCommand extends Command
{

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * SitePagesPathCommand constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
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
            ->setName('site:pages:set_path')
            ->setDescription('Установка path у страниц');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pageRepo = $this->em->getRepository(Pages::class);
        $pageRepo->updateAllPathRoute(false);
    }
}
