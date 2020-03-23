<?php declare(strict_types=1);

namespace Tests\Unit\Plans\Application\Find;

use Tests\BaseTestCase;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Connection;
use Ramsey\Uuid\Uuid;
use App\Plans\Application\Find\FindPlansByPeriodQuery;
use App\Plans\Domain\PlanView;
use App\Plans\Domain\Plans;
use App\Shared\Exceptions\BusinessException;
use Doctrine\DBAL\Query\Expression\ExpressionBuilder;
use PHPUnit\Framework\MockObject\MockObject;

final class FindPlansByPeriodTest extends BaseTestCase
{
    private MockObject $connection;
    private MockObject $queryBuilder;
    private MockObject $expressionBuilder;

    public function setUp(): void
    {
        parent::setUp();

        $this->connection = $this->createMock(Connection::class);
        $this->queryBuilder = $this->createMock(QueryBuilder::class);
        $this->expressionBuilder = $this->createMock(ExpressionBuilder::class);

        $this->queryBuilder->method("select")->willReturn($this->queryBuilder);
        $this->queryBuilder->method("from")->willReturn($this->queryBuilder);
        $this->queryBuilder->method("join")->willReturn($this->queryBuilder);
        $this->queryBuilder->method("having")->willReturn($this->queryBuilder);
        $this->queryBuilder->method("andHaving")->willReturn($this->queryBuilder);
        $this->queryBuilder->method("orderBy")->willReturn($this->queryBuilder);
        $this->queryBuilder->method("where")->willReturn($this->queryBuilder);
        $this->queryBuilder->method("andWhere")->willReturn($this->queryBuilder);

        $this->queryBuilder
            ->expects($this->exactly(10))
            ->method("expr")
            ->willReturn($this->expressionBuilder);

        $this->queryBuilder
            ->method("getParameters")
            ->willReturn([]);
    }

    /** @test */
    public function that_finds_plans()
    {
        $userId = (string) Uuid::uuid4();
        $timeMeterId = (string) Uuid::uuid4();

        $period = [
            "startDate" => "2020-02-02 14:54:24",
            "endDate" => "2020-02-27 15:21:29",
        ];

        $expectedPlans = [
            [
                "id"            => (string) Uuid::uuid4(),
                "time_meter_id" => $timeMeterId,
                "user_id"       => $userId,
                "start_date"    => "2020-02-02 00:00:00",
                "end_date"      => "2020-02-02 23:59:59",
                "min_time"      => 10,
                "max_time"      => 60,
                "name"          => "Work",
            ],
            [
                "id"            => (string) Uuid::uuid4(),
                "time_meter_id" => $timeMeterId,
                "user_id"       => $userId,
                "start_date"    => "2020-02-14 00:00:00",
                "end_date"      => "2020-02-21 23:59:59",
                "min_time"      => 10,
                "max_time"      => 60,
                "name"          => "Work",
            ],
        ];

        $this->queryBuilder
            ->expects($this->exactly(1))
            ->method("setParameters")
            ->with([
                "userId" => $userId,
                "startDate" => $period["startDate"],
                "endDate" => $period["endDate"],
            ])
            ->willReturn($this->queryBuilder);

        $this->connection->method("createQueryBuilder")->willReturn($this->queryBuilder);
        $this->connection
            ->expects($this->exactly(3))
            ->method("fetchAll")
            ->willReturnOnConsecutiveCalls(
                $expectedPlans, [], []
            );

        $this->container->set(Connection::class, $this->connection);

        $plans = $this->queryBus->handle(new FindPlansByPeriodQuery(
            $userId,
            $period["startDate"],
            $period["endDate"],
        ));

        $expectedPlans = new Plans(array_map(
            fn ($plan) => PlanView::create($plan),
            $expectedPlans
        ));

        $this->assertSame($expectedPlans->count(), $plans->count());

        $plans = $plans->toArray();

        foreach ($expectedPlans->toArray() as $key => $expectedPlan) {
            $this->assertSame($expectedPlan->id(), $plans[$key]->id());
            $this->assertSame($expectedPlan->userId(), $plans[$key]->userId());
            $this->assertSame($expectedPlan->timeMeterId(), $plans[$key]->timeMeterId());
            $this->assertSame($expectedPlan->startDate(), $plans[$key]->startDate());
            $this->assertSame($expectedPlan->endDate(), $plans[$key]->endDate());
            $this->assertSame($expectedPlan->minTime(), $plans[$key]->minTime());
            $this->assertSame($expectedPlan->maxTime(), $plans[$key]->maxTime());
        }
    }

    /** @test */
    public function that_throws_exception()
    {
        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage("Could not fetch plans.");

        $this->connection->method("createQueryBuilder")->willReturn($this->queryBuilder);

        $this->connection
            ->expects($this->once())
            ->method("fetchAll")
            ->willReturn(false);

        $this->container->set(Connection::class, $this->connection);

        $this->queryBus->handle(new FindPlansByPeriodQuery(
            (string) Uuid::uuid4(),
            "2020-02-02 15:12:21",
            "2020-02-12 15:12:21",
        ));
    }
}
