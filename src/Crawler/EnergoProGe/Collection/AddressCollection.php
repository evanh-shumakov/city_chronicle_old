<?php

namespace App\Crawler\EnergoProGe\Collection;

use App\Crawler\EnergoProGe\Entity\Address;

class AddressCollection implements \Iterator
{
    private array $collection = [];

    private int $pointer = 0;

    public function add(Address $address): void
    {
        $this->collection[] = $address;
    }
    public function current(): Address
    {
        return $this->collection[$this->pointer];
    }

    public function next(): void
    {
        $this->pointer++;
    }

    public function key(): mixed
    {
        return $this->pointer;
    }

    public function valid(): bool
    {
        return isset($this->collection[$this->pointer]);
    }

    public function rewind(): void
    {
        $this->pointer = 0;
    }

    public function __toString(): string
    {
        return implode(", ", $this->collection);
    }

    public function implode(string $separator): string
    {
        return implode($separator, $this->collection);
    }
}
