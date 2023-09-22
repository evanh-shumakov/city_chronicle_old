<?php

namespace App\Crawler\EnergoProGe\Collection;

use App\Crawler\EnergoProGe\Entity\Content;

final class ContentCollection implements \Iterator
{
    /**
     * @var array<Content>
     */
    private array $collection;

    private int $key;

    public function add(Content $content): void
    {
        $this->collection[] = $content;
    }

    public function current(): Content
    {
        return $this->collection[$this->key];
    }

    public function next(): void
    {
        ++ $this->key;
    }

    public function key(): int
    {
        return $this->key();
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
