<?php declare(strict_types=1);

namespace Tests\Unit\Measurements\Application\Find;

use Tests\BaseTestCase;
use Ramsey\Uuid\Uuid;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Connection;
use App\Measurements\Domain\Measurements;
use App\Measurements\Domain\MeasurementView;
use App\Measurements\Application\Find\FindMeasurementsByPlanQuery;
use App\Shared\Contracts\ErrorMessageInterface;
use App\Shared\Exceptions\BusinessException;

final class FindMeasurementsByPlanTest extends BaseTestCase
{
    /** @test */
    public function that_finds_measurements()
    {
        $planId = (string) Uuid::uuid4();

        $expectedMeasurements = [
            [
                'id'      => (string) Uuid::uuid4(),
                'plan_id' => $planId,
                'status'  => 'started',
                'stopped_at' => null,
                'created_at' => "2020-02-18 15:20:20",
            ],
            [
                'id'         => (string) Uuid::uuid4(),
                'plan_id'    => $planId,
                'status'     => 'stopped',
                'stopped_at' => "2020-02-20 15:41:24",
                'created_at' => "2020-02-19 15:41:24",
            ],
        ];

        $queryBuilder = $this->createMock(QueryBuilder::class);

        $queryBuilder->method("select")->willReturn($queryBuilder);
        $queryBuilder->method("from")->willReturn($queryBuilder);
        $queryBuilder->method("where")->willReturn($queryBuilder);
        $queryBuilder->method("orderBy")->willReturn($queryBuilder);

        $queryBuilder
            ->expects($this->once())
            ->method("setParameter")
            ->with("planId", $planId)
            ->willReturn($queryBuilder);

        $queryBuilder
            ->method("getParameters")
            ->willReturn([]);

        $connection = $this->createMock(Connection::class);
        $connection->method("createQueryBuilder")->willReturn($queryBuilder);

        $connection
            ->expects($this->once())
            ->method("fetchAll")
            ->willReturn($expectedMeasurements);

        $this->container->set(Connection::class, $connection);

        $measurements = $this->queryBus->handle(new FindMeasurementsByPlanQuery($planId));

        $expectedMeasurements = new Measurements(array_map(
            fn ($measurement) => MeasurementView::create($measurement),
            $expectedMeasurements
        ));

        $this->assertSame($expectedMeasurements->count(), $measurements->count());

        $measurements = $measurements->toArray();

        foreach ($expectedMeasurements->toArray() as $key => $expectedMeasurement) {
            $this->assertSame($expectedMeasurement->toArray(), $measurements[$key]->toArray());
        }
    }

    /** @test */
    public function that_throws_expection_on_fail()
    {
        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage(ErrorMessageInterface::MEASUREMENTS_NOT_FOUND);

        $planId = (string) Uuid::uuid4();

        $queryBuilder = $this->createMock(QueryBuilder::class);

        $queryBuilder->method("select")->willReturn($queryBuilder);
        $queryBuilder->method("from")->willReturn($queryBuilder);
        $queryBuilder->method("where")->willReturn($queryBuilder);
        $queryBuilder->method("orderBy")->willReturn($queryBuilder);

        $queryBuilder
            ->expects($this->once())
            ->method("setParameter")
            ->with("planId", $planId)
            ->willReturn($queryBuilder);

        $queryBuilder
            ->method("getParameters")
            ->willReturn([]);

        $connection = $this->createMock(Connection::class);
        $connection->method("createQueryBuilder")->willReturn($queryBuilder);

        $connection
            ->expects($this->once())
            ->method("fetchAll")
            ->willReturn(false);

        $this->container->set(Connection::class, $connection);

        $this->queryBus->handle(new FindMeasurementsByPlanQuery($planId));
    }
}
