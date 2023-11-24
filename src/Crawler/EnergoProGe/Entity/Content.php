<?php

namespace App\Crawler\EnergoProGe\Entity;

use App\Crawler\EnergoProGe\Collection\AddressCollection;

final readonly class Content
{
    public function __construct(
        public AddressCollection $addresses,
        public ?\DateTime $startDate,
        public ?\DateTime $endDate,
        public string $serviceCenter,
        public string $original,
    ) {}

    public function getStartDateString(string $format): string
    {
        if (is_null($this->startDate)) {
            return '';
        }

        return $this->startDate->format($format);
    }

    public function getEndDateString(string $format): string
    {
        if (is_null($this->endDate)) {
            return '';
        }

        return $this->endDate->format($format);
    }
}
