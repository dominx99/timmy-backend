<?php declare(strict_types=1);

namespace Tests\Unit\Measurements\Application\Create;

use App\Measurements\Application\Create\CreateMeasurementForPlanCommand;
use Tests\BaseTestCase;
use Doctrine\DBAL\Query\QueryBuilder;
use Ramsey\Uuid\Uuid;
use Doctrine\DBAL\Connection;

final class StartMeasurementForPlanTest extends BaseTestCase
{
    /** @test */
    public function that_creates_measurement()
    {
        $planId = (string) Uuid::uuid4();
        $measurementId = (string) Uuid::uuid4();

        $queryBuilder = $this->createMock(QueryBuilder::class);

        $queryBuilder->method("insert")->willReturn($queryBuilder);
        $queryBuilder->method("values")->willReturn($queryBuilder);

        $queryBuilder
            ->expects($this->once())
            ->method("setParameters")
            ->with([
                "measurementId" => $measurementId,
                "planId"        => $planId,
            ])
            ->willReturn($queryBuilder);

        $queryBuilder
            ->expects($this->once())
            ->method("execute")
            ->willReturn(1);

        $connection = $this->createMock(Connection::class);
        $connection->method("createQueryBuilder")->willReturn($queryBuilder);

        $this->container->set(Connection::class, $connection);

        $this->commandBus->handle(new CreateMeasurementForPlanCommand(
            $measurementId,
            $planId,
        ));
    }
}
