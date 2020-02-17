<?php declare(strict_types=1);

namespace Tests\Unit\Shared\Infrastructure\Bus;

use App\Shared\Exceptions\SystemException;
use Tests\BaseTestCase;
use Tests\Unit\Shared\Infrastructure\Bus\DummyQuery;
use Tests\Unit\Shared\Infrastructure\Bus\DummyQueryHandler;

final class QueryBusTest extends BaseTestCase
{
    /** @test */
    public function that_handles_query()
    {
        $handler = $this->createMock(DummyQueryHandler::class);

        $handler
            ->expects($this->once())
            ->method("handle");

        $this->container->set(DummyQuery::class, $handler);

        $this->queryBus->handle(new DummyQuery("test"));
    }

    /** @test */
    public function that_throws_exception_when_command_does_handler_not_found()
    {
        $this->expectException(SystemException::class);

        $this->queryBus->handle(new DummyQuery("test"));
    }
}
