<?php

namespace App\Command;

use App\Services\UpdatePricesService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdatePriceCommand extends Command
{
    protected static $defaultName = 'app:update:price';
    protected UpdatePricesService $updatePricesService;

    /**
     * UpdatePriceCommand constructor.
     * @param UpdatePricesService $updatePricesService
     * @param string|null $name
     */
    public function __construct(UpdatePricesService $updatePricesService, string $name = null)
    {
        parent::__construct($name);
        $this->updatePricesService = $updatePricesService;
    }


    protected function configure()
    {
        $this
            ->setDescription('
            
            обновление городов-цен')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $updated = $this->updatePricesService->execute();

        $io->success(
            sprintf("Обновлено %d товаров", $updated)
        );

        return 0;
    }
}
