<?php declare(strict_types=1);

namespace App\Shared\Application\Validation;

final class ErrorBag
{
    private array $bag = [];

    public function add(string $field, ErrorsCollection $errors)
    {
        $this->bag[$field] = $errors;
    }

    public function empty(): bool
    {
        $result = true;

        foreach ($this->bag as $errors) {
            if (! $errors->empty()) {
                $result = false;
            }
        };

        return $result;
    }

    public function toArray(): array
    {
        return array_map(fn ($errors) => $errors->toArray(), $this->bag);
    }
}
