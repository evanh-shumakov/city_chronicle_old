<?php

namespace App\Crawler\EnergoProGe\Entity;

readonly class Address
{
    public function __construct(
        public string $street,
    ) {
    }

    public function __toString(): string
    {
        return $this->street;
    }
}
