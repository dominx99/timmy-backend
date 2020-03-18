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

    public function count(): int
    {
        return count($this->items);
    }

    public function map(callable $callback): self
    {
        return new static(array_map($callback, $this->items));
    }

    public function toArray(): array
    {
        return $this->items;
    }

    /**
        @return mixed
     */
    public function find(callable $callback)
    {
        $items = array_values(array_filter($this->items, $callback));

        if (isset($items[0])) {
            return $items[0];
        }

        return null;
    }
}
