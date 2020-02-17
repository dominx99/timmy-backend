<?php declare(strict_types=1);

namespace Tests\Unit\Shared\Infrastructure\Bus;

use App\Shared\Contracts\Command;

final class DummyCommand implements Command
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function name(): string
    {
        return $this->name;
    }
}
