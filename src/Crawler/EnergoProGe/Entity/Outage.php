<?php

namespace App\Crawler\EnergoProGe\Entity;

use App\Crawler\EnergoProGe\Collection\AddressCollection;
use App\Crawler\EnergoProGe\Crawler;
use App\Interface\NewsSource;

readonly class Outage implements NewsSource
{
    public function __construct(
        public \DateTime $dateStart,
        public \DateTime $dateEnd,
        public string $city,
        public AddressCollection $addresses,
    ){
    }

    public function composeNewsTitle(): string
    {
        return "Power Outage " . $this->dateStart->format("F j");
    }

    public function composeNewsPreview(): string
    {
        return <<<HEREDOC
            Be aware that there is a planned power outage for several addresses
            in Kutaisi on {$this->dateStart->format('F j')} 
            from {$this->dateStart->format('H:i')}
            to {$this->dateEnd->format('H:i')}:
            HEREDOC;
    }

    public function composeNewsContent(): string
    {
        return $this->addresses->implode("<br>");
    }

    public function getSourceUrl(): string
    {
        return Crawler::OUTAGE_LIST_URL;
    }
}
