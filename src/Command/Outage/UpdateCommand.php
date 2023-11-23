<?php

namespace App\Command\Outage;

use App\Crawler\EnergoProGe;
use App\Crawler\EnergoProGe\Enum\ServiceCenter;
use App\Factory\Entity\NewsFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:outage:update', description: 'Update outages news')]
class UpdateCommand extends Command
{
    protected function configure(): void
    {
        $this->addArgument(
            'date',
            InputArgument::OPTIONAL,
            'A date/time string',
            'tomorrow'
        );
    }

    public function __construct(readonly EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $io = new SymfonyStyle($input, $output);
        $crawler = new EnergoProGe\Crawler();

        $outages = $crawler->fetchOutages()
            ->getByServiceCentre(ServiceCenter::KUTAISI->value)
            ->getByDate(new \DateTime('today'));

        foreach ($outages as $outage) {
            $news = NewsFactory::makeNews($outage);
            $this->entityManager->persist($news);
        }

        $this->entityManager->flush();

        $io->success(sprintf("Added %d new outages.", $outages->count()));

        return Command::SUCCESS;
    }
}
