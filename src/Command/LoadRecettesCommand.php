<?php

namespace App\Command;

use App\Service\RecetteLoader;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:load-recettes',
    description: 'Add a short description for your command',
)]
class LoadRecettesCommand extends Command
{
    // Définir un nom explicite pour la commande
    protected static $defaultName = 'app:load-recettes';

    private RecetteLoader $recetteLoader;

    public function __construct(RecetteLoader $recetteLoader)
    {
        $this->recetteLoader = $recetteLoader;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Charge des recettes dans la base de données')
            ->setHelp('Cette commande charge des recettes préconfigurées dans la base de données.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Appel du service pour charger les recettes
        $this->recetteLoader->loadRecettes();
        $output->writeln('Les recettes ont été chargées avec succès !');

        return Command::SUCCESS;
    }
}



