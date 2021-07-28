<?php

namespace App\Command;

use App\EloCalculator\EloCalculator;
use App\Repository\SpelerRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CalculateEloChainCommand extends Command
{
    protected static $defaultName = 'CalculateEloChain';
    protected static $defaultDescription = 'Add a short description for your command';

    /**
     * @var EloCalculator
     */
    private $calculator;
    /**
     * @var SpelerRepository
     */
    private $spelerRepository;

    public function __construct(string $name = null, EloCalculator $calculator, SpelerRepository $spelerRepository)
    {
        parent::__construct($name);

        $this->calculator = $calculator;
        $this->spelerRepository = $spelerRepository;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return Command::SUCCESS;
    }
}
