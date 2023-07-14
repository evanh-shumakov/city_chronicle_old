<?php

namespace App\Crawler\EnergoProGe\Entity;

use App\Crawler\EnergoProGe\Collection\AddressCollection;

readonly class Outage
{
    public function __construct(
        public \DateTime $dateStart,
        public \DateTime $dateEnd,
        public string $city,
        public AddressCollection $addresses,
    ){
    }
}
