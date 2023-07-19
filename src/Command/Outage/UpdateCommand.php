<?php

namespace App\Command\Outage;

use App\Crawler\EnergoProGe;
use App\Factory\NewsFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:outage:update',
    description: 'Update outages news',
)]
class UpdateCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $crawler = new EnergoProGe\Crawler();

        $crawler->setCity($crawler::KUTAISI);
        $outages = $crawler->fetchOutageList();
        foreach ($outages as $outage) {
            $news = NewsFactory::makeNews($outage);
            $this->entityManager->persist($news);
        }
        $this->entityManager->flush();

        $io->success(sprintf("Added %d new outages.", $outages->count()));

        return Command::SUCCESS;
    }
}
