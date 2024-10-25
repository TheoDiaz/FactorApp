<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:cleanup-db',
    description: 'Supprime les enregistrements obsolètes de la base de données.',
)]
class CleanupDbCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Nettoyage des enregistrements obsolètes...');
        
        $query = $this->entityManager->createQuery(
            'DELETE FROM App\Entity\OldRecord o WHERE o.date < :date'
        );
        $query->setParameter('date', new \DateTime('-1 year'));
        $result = $query->execute();

        $output->writeln("$result enregistrements supprimés.");
        return Command::SUCCESS;
    }
}
