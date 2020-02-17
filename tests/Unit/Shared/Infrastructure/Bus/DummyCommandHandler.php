<?php declare(strict_types=1);

namespace Tests\Unit\Shared\Infrastructure\Bus;

use App\Shared\Contracts\CommandHandler;

class DummyCommandHandler implements CommandHandler
{
    public function handle(DummyCommand $command): void
    {
        $command->name();
    }
}
