<?php

namespace App\Crawler\EnergoProGe\Entity;

use App\Crawler\EnergoProGe\Collection\AddressCollection;
use App\Crawler\EnergoProGe\Crawler;
use App\Interface\NewsSource;

final readonly class Outage implements NewsSource
{
    public function __construct(public Content $content)
    {
    }

    public function composeNewsTitle(): string
    {
        return "Power Outage " . $this->content->startDate->format("F j");
    }

    public function composeNewsPreview(): string
    {
        return <<<HEREDOC
            Be aware that there is a planned power outage for several addresses
            in Kutaisi on {$this->content->startDate->format('F j')} 
            from {$this->content->startDate->format('H:i')}
            to {$this->content->endDate->format('H:i')}:
            HEREDOC;
    }

    public function composeNewsContent(): string
    {
        return $this->content->addresses->implode("<br>");
    }

    public function getSourceUrl(): string
    {
        return Crawler::OUTAGE_LIST_URL;
    }
}
