<?php declare(strict_types=1);

namespace Tests\Unit\Shared\Infrastructure\Bus;

use App\Shared\Contracts\QueryHandler;

class DummyQueryHandler implements QueryHandler
{
    public function handle(DummyQuery $command): string
    {
        return $command->name();
    }
}
