<?php declare(strict_types=1);

namespace App\Shared\Application\Collection;

abstract class Collection
{
    private array $items;

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function push($item): void
    {
        $this->items[] = $item;
    }

    public function empty(): bool
    {
        return empty($this->items);
    }

    public function map(callable $callback): self
    {
        return new static(array_map($callback, $this->items));
    }

    public function toArray(): array
    {
        return $this->items;
    }
}
