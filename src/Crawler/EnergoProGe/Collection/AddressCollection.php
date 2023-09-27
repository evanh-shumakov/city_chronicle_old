<?php

namespace App\Crawler\EnergoProGe\Collection;

use App\Crawler\EnergoProGe\Entity\Address;

/**
 * @implements \Iterator<int, Address>
 */
class AddressCollection implements \Iterator
{
    /**
     * @var Address[]
     */
    private array $collection = [];

    private int $key = 0;

    public function add(Address $address): void
    {
        $this->collection[] = $address;
    }
    public function count(): int
    {
        return count($this->collection);
    }

    /**
     * @return array<Address>
     */
    public function toArray(): array
    {
        return $this->collection;
    }

    public function implode(string $separator): string
    {
        $list = [];
        foreach ($this as $address) {
            $list[] = $address->origin;
        }
        return implode($separator, $list);
    }

    public function current(): Address
    {
        return $this->collection[$this->key];
    }

    public function next(): void
    {
        ++ $this->key;
    }

    public function key(): mixed
    {
        return $this->key;
    }

    public function valid(): bool
    {
        return isset($this->collection[$this->key]);
    }

    public function rewind(): void
    {
        $this->key = 0;
    }
}
