<?php declare(strict_types=1);

namespace Tests\Unit\Plans\Application\Create;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Tests\BaseTestCase;
use Ramsey\Uuid\Uuid;
use App\Plans\Application\Create\CreatePlanCommand;

final class CreatePlanTest extends BaseTestCase
{
    /** @test */
    public function that_creates_time_meter()
    {
        $planId      = (string) Uuid::uuid4();
        $timeMeterId = (string) Uuid::uuid4();
        $userId      = (string) Uuid::uuid4();

        $startDate = "2020-02-18 00:00:00";
        $endDate   = "2020-02-18 23:59:59";

        $minTime = 3600;
        $maxTime = 4800;

        $queryBuilder = $this->createMock(QueryBuilder::class);

        $queryBuilder
            ->method("insert")
            ->willReturn($queryBuilder);

        $queryBuilder
            ->method("values")
            ->willReturn($queryBuilder);

        $queryBuilder
            ->expects($this->once())
            ->method("setParameters")
            ->with([
                "planId"      => $planId,
                "timeMeterId" => $timeMeterId,
                "userId"      => $userId,
                "startDate"   => $startDate,
                "endDate"     => $endDate,
                "minTime"     => $minTime,
                "maxTime"     => $maxTime,
            ])
            ->willReturn($queryBuilder);

        $queryBuilder
            ->expects($this->once())
            ->method("execute")
            ->willReturn(1);

        $connection = $this->createMock(Connection::class);
        $connection->method("createQueryBuilder")->willReturn($queryBuilder);

        $this->container->set(Connection::class, $connection);

        $this->commandBus->handle(new CreatePlanCommand(
            $planId,
            $timeMeterId,
            $userId,
            $startDate,
            $endDate,
            $minTime,
            $maxTime,
        ));
    }
}
