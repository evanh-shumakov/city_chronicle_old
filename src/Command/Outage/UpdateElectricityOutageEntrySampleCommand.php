<?php

namespace App\Command\Outage;

use App\Crawler\EnergoProGe;
use App\Crawler\EnergoProGe\Enum\ServiceCenter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: 'app:outage:updateEntrySample',
    description: 'Update outage entry sample for test use',
)]
class UpdateElectricityOutageEntrySampleCommand extends Command
{
    const FILE = 'upload/electricity_outage_entry_sample.txt';

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $io = new SymfonyStyle($input, $output);
        $crawler = new EnergoProGe\Crawler();

        $filesystem = new Filesystem();

        $outages = $crawler->fetchOutages()
            ->getByServiceCentre(ServiceCenter::KUTAISI->value);

        if ($outages->isEmpty()) {
            $io->caution('Can\'t update sample. There are no entries.');
            return Command::FAILURE;
        }

        if ($filesystem->exists(self::FILE)
            && ! $this->askShouldProceed($io)) {
            return Command::SUCCESS;
        }

        $content = $outages->current()->content->original;
        $filesystem->dumpFile(self::FILE, $content);

        $io->success(sprintf('File have been saved (%s)', self::FILE));

        return Command::SUCCESS;
    }

    private function askShouldProceed(SymfonyStyle $io): bool
    {
        $answer = $io->ask('A sample file already exists. Rewrite? (y/n)');

        if (! is_string($answer)) {
            return false;
        }

        return strtolower($answer) === 'y';
    }
}
