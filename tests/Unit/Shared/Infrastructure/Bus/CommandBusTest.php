<?php declare(strict_types=1);

namespace Tests\Unit\Shared\Infrastructure\Bus;

use App\Shared\Exceptions\SystemException;
use Tests\BaseTestCase;

final class CommandBusTest extends BaseTestCase
{
    /** @test */
    public function that_handles_command()
    {
        $handler = $this->createMock(DummyCommandHandler::class);

        $handler
            ->expects($this->once())
            ->method("handle");

        $this->container->set(DummyCommand::class, $handler);

        $this->commandBus->handle(new DummyCommand("test"));
    }

    /** @test */
    public function that_throws_exception_when_command_does_handler_not_found()
    {
        $this->expectException(SystemException::class);

        $this->commandBus->handle(new DummyCommand("test"));
    }
}
