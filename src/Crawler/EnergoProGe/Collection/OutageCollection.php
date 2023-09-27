<?php

namespace App\Crawler\EnergoProGe\Collection;

use App\Crawler\EnergoProGe\Entity\Outage;

/**
 * @implements \Iterator<int, Outage>
 */
class OutageCollection implements \Iterator
{
    /**
     * @var array<Outage>
     */
    private array $collection = [];

    private int $key = 0;

    public function add(Outage $param): void
    {
        $this->collection[] = $param;
    }

    public function count(): int
    {
        return count($this->collection);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function getByServiceCentre(string $serviceCenter): self
    {
        $outages = new self();
        foreach ($this as $outage) {
            if ($outage->content->serviceCenter === $serviceCenter) {
                $outages->add($outage);
            }
        }
        return $outages;
    }

    public function getByDate(\DateTime $dateTime): self
    {
        $format = 'Ymd';
        $date = $dateTime->format($format);
        $outages = new self();
        foreach ($this as $outage) {
            if ($outage->content->startDate->format($format) === $date) {
                $outages->add($outage);
            }
        }
        return $outages;
    }

    public function current(): Outage
    {
        return $this->collection[$this->key];
    }

    public function next(): void
    {
        ++ $this->key;
    }

    public function key(): int
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
