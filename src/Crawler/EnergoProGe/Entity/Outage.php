<?php

namespace App\Crawler\EnergoProGe\Entity;

use App\Crawler\EnergoProGe\Crawler;
use App\Interface\NewsSource;

final readonly class Outage implements NewsSource
{
    public function __construct(public Content $content) {}

    public function composeNewsTitle(): string
    {
        return "Power Outage " . $this->content->getStartDateString('F j');
    }

    public function composeNewsPreview(): string
    {
        return <<<HEREDOC
            Be aware that there is a planned power outage for several addresses
            in Kutaisi on {$this->content->getStartDateString('F j')} 
            from {$this->content->getStartDateString('H:i')}
            to {$this->content->getEndDateString('H:i')}:
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
