<?php declare(strict_types=1);

namespace Tests\Unit\Plans\Application\Find;

use App\Plans\Application\Find\IsPlanOutdatedQuery;
use App\Shared\Contracts\Calendar\CalendarContract;
use Tests\BaseTestCase;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\ResultStatement;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Uuid;
use \DateTime;

final class IsPlanOutdatedTest extends BaseTestCase
{
    private string $planId;
    private MockObject $connection;
    private MockObject $queryBuilder;
    private MockObject $statement;

    public function setUp(): void
    {
        parent::setUp();

        $this->planId = (string) Uuid::uuid4();
        $this->connection = $this->createMock(Connection::class);
        $this->queryBuilder = $this->createMock(QueryBuilder::class);
        $this->statement = $this->createMock(ResultStatement::class);

        $this->queryBuilder->method("select")->willReturn($this->queryBuilder);
        $this->queryBuilder->method("from")->willReturn($this->queryBuilder);
        $this->queryBuilder->method("where")->willReturn($this->queryBuilder);
        $this->queryBuilder->method("andWhere")->willReturn($this->queryBuilder);

        $now = new DateTime("now");
        $calendar = $this->createMock(CalendarContract::class);
        $calendar->method("now")->willReturn($now);

        $this->queryBuilder
            ->expects($this->once())
            ->method("setParameters")
            ->with([
                "planId" => $this->planId,
                "now"    => $now->format("Y-m-d H:i:s"),
            ])
            ->willReturn($this->queryBuilder);
    }

    /** @test */
    public function that_returns_that_plan_is_started()
    {
        $this->statement
            ->expects($this->once())
            ->method("fetch")
            ->willReturn(["is_outdated" => true]);

        $this->queryBuilder
            ->expects($this->once())
            ->method("execute")
            ->willReturn($this->statement);

        $this->connection->method("createQueryBuilder")->willReturn($this->queryBuilder);

        $this->container->set(Connection::class, $this->connection);

        $isPlanOutdated = $this->queryBus->handle(new IsPlanOutdatedQuery($this->planId));

        $this->assertTrue($isPlanOutdated);
    }

    /** @test */
    public function that_returns_that_plan_is_not_started()
    {
        $this->statement
            ->expects($this->once())
            ->method("fetch")
            ->willReturn(["is_outdated" => false]);

        $this->queryBuilder
            ->expects($this->once())
            ->method("execute")
            ->willReturn($this->statement);

        $this->connection->method("createQueryBuilder")->willReturn($this->queryBuilder);

        $this->container->set(Connection::class, $this->connection);

        $isPlanOutdated = $this->queryBus->handle(new IsPlanOutdatedQuery($this->planId));

        $this->assertFalse($isPlanOutdated);
    }

}
