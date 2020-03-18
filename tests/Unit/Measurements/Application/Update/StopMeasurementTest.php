<?php declare(strict_types=1);

namespace Tests\Unit\Measurements\Application\Update;

use Tests\BaseTestCase;
use Doctrine\DBAL\Query\QueryBuilder;
use Ramsey\Uuid\Uuid;
use Doctrine\DBAL\Connection;
use App\Measurements\Application\Update\StopMeasurementCommand;
use App\Shared\Contracts\Calendar\CalendarContract;

final class StopMeasurementTest extends BaseTestCase
{
    /** @test */
    public function that_stops_measurement()
    {
        $measurementId = (string) Uuid::uuid4();

        $queryBuilder = $this->createMock(QueryBuilder::class);

        $now = new \DateTime("now");

        $calendar = $this->createMock(CalendarContract::class);

        $calendar->method("now")->willReturn($now);

        $this->container->set(CalendarContract::class, $calendar);

        $queryBuilder->method("update")->willReturn($queryBuilder);
        $queryBuilder->method("where")->willReturn($queryBuilder);
        $queryBuilder->expects($this->exactly(2))->method("set")->willReturn($queryBuilder);

        $queryBuilder
            ->expects($this->once())
            ->method("setParameters")
            ->with([
                "measurementId" => $measurementId,
                "stoppedAt"     => $now->format("Y-m-d H:i:s"),
                "status"        => "stopped",
            ])
            ->willReturn($queryBuilder);

        $connection = $this->createMock(Connection::class);
        $connection->method("createQueryBuilder")->willReturn($queryBuilder);

        $this->container->set(Connection::class, $connection);

        $this->commandBus->handle(new StopMeasurementCommand($measurementId));
    }
}
