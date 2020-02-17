<?php declare(strict_types=1);

namespace Tests\Unit\TimeMeters;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Tests\BaseTestCase;
use Ramsey\Uuid\Uuid;
use App\TimeMeters\Application\Create\CreateTimeMeterCommand;

final class CreateTimeMeterTest extends BaseTestCase
{
    /** @test */
    public function that_creates_time_meter()
    {
        $userId = (string) Uuid::uuid4();
        $timeMeterId = (string) Uuid::uuid4();
        $queryBuilder = $this->createMock(QueryBuilder::class);

        $queryBuilder
            ->method("insert")
            ->willReturn($queryBuilder);

        $queryBuilder
            ->method("values")
            ->willReturn($queryBuilder);

        $queryBuilder
            ->expects($this->exactly(3))
            ->method("setParameter")
            ->withConsecutive(
                ["id", $timeMeterId],
                ["name", "Work"],
                ["user_id", $userId]
            )
            ->willReturn($queryBuilder);

        $queryBuilder
            ->expects($this->once())
            ->method("execute")
            ->willReturn(1);

        $connection = $this->createMock(Connection::class);
        $connection->method("createQueryBuilder")->willReturn($queryBuilder);

        $this->container->set(Connection::class, $connection);

        $this->commandBus->handle(new CreateTimeMeterCommand($timeMeterId, $userId, "Work"));
    }
}
