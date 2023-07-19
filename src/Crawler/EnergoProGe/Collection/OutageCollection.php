<?php

namespace App\Crawler\EnergoProGe\Collection;

use App\Crawler\EnergoProGe\Entity\Outage;

class OutageCollection implements \Iterator
{
    private array $collection = [];

    private int $pointer = 0;

    public function add(Outage $param): void
    {
        $this->collection[] = $param;
    }

    public function count(): int
    {
        return count($this->collection);
    }

    public function current(): Outage
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
}
