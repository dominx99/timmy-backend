<?php declare(strict_types=1);

namespace Tests\Unit\Shared\Infrastructure\Bus;

use App\Shared\Contracts\Query;

final class DummyQuery implements Query
{
    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
