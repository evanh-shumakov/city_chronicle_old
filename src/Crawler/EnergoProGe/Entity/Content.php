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
}
