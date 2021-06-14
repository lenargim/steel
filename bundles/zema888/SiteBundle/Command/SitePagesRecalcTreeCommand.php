<?php

namespace SiteBundle\Command;

use Doctrine\ORM\EntityManager;
use SiteBundle\Entity\Pages;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SitePagesRecalcTreeCommand extends Command
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
            ->setName('site:pages:recalc')
            ->setDescription('Перерасчет дерева');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pageRepo = $this->em->getRepository(Pages::class);
        $pageRepo->verify();
        $pageRepo->recover();
        $this->em->flush();
    }
}
