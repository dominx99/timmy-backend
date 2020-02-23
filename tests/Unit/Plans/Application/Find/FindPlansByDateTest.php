<?php declare(strict_types=1);

namespace Tests\Unit\Plans\Application\Find;

use Tests\BaseTestCase;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Connection;
use Ramsey\Uuid\Uuid;
use App\Plans\Application\Find\FindPlansByDateQuery;
use App\Plans\Domain\PlanView;
use App\Plans\Domain\Plans;
use App\Shared\Exceptions\BusinessException;
use App\TimeMeters\Application\Find\FindAllTimeMetersQuery;

final class FindPlansByDateTest extends BaseTestCase
{
    /**
        @test
        @depends Tests\Unit\Plans\Application\Create\CreatePlanTest::that_creates_plan
     */
    public function that_finds_plans()
    {
        $userId = (string) Uuid::uuid4();
        $timeMeterId = (string) Uuid::uuid4();

        $date = "2020-02-02 14:54:24";

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
                "start_date"    => "2020-02-01 00:00:00",
                "end_date"      => "2020-02-08 23:59:59",
                "min_time"      => 10,
                "max_time"      => 60,
                "name"          => "Work",
            ],
        ];

        $queryBuilder = $this->createMock(QueryBuilder::class);

        $queryBuilder
            ->method("select")
            ->willReturn($queryBuilder);

        $queryBuilder
            ->method("from")
            ->willReturn($queryBuilder);

        $queryBuilder
            ->method("join")
            ->willReturn($queryBuilder);

        $queryBuilder
            ->expects($this->once())
            ->method("having")
            ->willReturn($queryBuilder);

        $queryBuilder
            ->expects($this->exactly(2))
            ->method("andHaving")
            ->willReturn($queryBuilder);

        $queryBuilder
            ->method("orderBy")
            ->willReturn($queryBuilder);

        $queryBuilder
            ->expects($this->exactly(2))
            ->method("setParameter")
            ->withConsecutive(
                ["userId", $userId],
                ["date", $date],
            )
            ->willReturn($queryBuilder);

        $queryBuilder
            ->method("getParameters")
            ->willReturn([]);

        $connection = $this->createMock(Connection::class);
        $connection->method("createQueryBuilder")->willReturn($queryBuilder);

        $connection
            ->expects($this->once())
            ->method("fetchAll")
            ->willReturn($expectedPlans);

        $this->container->set(Connection::class, $connection);

        $plans = $this->queryBus->handle(new FindPlansByDateQuery($userId, $date));

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

        $queryBuilder = $this->createMock(QueryBuilder::class);

        $queryBuilder
            ->method("select")
            ->willReturn($queryBuilder);

        $queryBuilder
            ->method("from")
            ->willReturn($queryBuilder);

        $queryBuilder
            ->method("join")
            ->willReturn($queryBuilder);

        $queryBuilder
            ->method("having")
            ->willReturn($queryBuilder);

        $queryBuilder
            ->method("andHaving")
            ->willReturn($queryBuilder);

        $queryBuilder
            ->method("orderBy")
            ->willReturn($queryBuilder);

        $queryBuilder
            ->method("setParameter")
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

        $this->queryBus->handle(new FindPlansByDateQuery(
            (string) Uuid::uuid4(),
            "2020-02-02 15:12:21",
        ));
    }
}
