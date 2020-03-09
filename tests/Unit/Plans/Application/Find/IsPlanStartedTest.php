<?php declare(strict_types=1);

namespace Tests\Unit\Plans\Application\Find;

use App\Plans\Application\Find\IsPlanStartedQuery;
use Tests\BaseTestCase;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\ResultStatement;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Uuid;

final class IsPlanStartedTest extends BaseTestCase
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
        $this->queryBuilder->method("join")->willReturn($this->queryBuilder);
        $this->queryBuilder->method("where")->willReturn($this->queryBuilder);
        $this->queryBuilder->method("andWhere")->willReturn($this->queryBuilder);

        $this->queryBuilder
            ->expects($this->once())
            ->method("setParameters")
            ->with([
                "planId" => $this->planId,
                "status" => "started",
            ])
            ->willReturn($this->queryBuilder);
    }

    /** @test */
    public function that_returns_that_plan_is_started()
    {
        $this->statement
            ->expects($this->once())
            ->method("fetch")
            ->willReturn(["is_started" => true]);

        $this->queryBuilder
            ->expects($this->once())
            ->method("execute")
            ->willReturn($this->statement);

        $this->connection->method("createQueryBuilder")->willReturn($this->queryBuilder);

        $this->container->set(Connection::class, $this->connection);

        $isPlanStarted = $this->queryBus->handle(new IsPlanStartedQuery($this->planId));

        $this->assertTrue($isPlanStarted);
    }

    /** @test */
    public function that_returns_that_plan_is_not_started()
    {
        $this->statement
            ->expects($this->once())
            ->method("fetch")
            ->willReturn(["is_started" => false]);

        $this->queryBuilder
            ->expects($this->once())
            ->method("execute")
            ->willReturn($this->statement);

        $this->connection->method("createQueryBuilder")->willReturn($this->queryBuilder);

        $this->container->set(Connection::class, $this->connection);

        $isPlanStarted = $this->queryBus->handle(new IsPlanStartedQuery($this->planId));

        $this->assertFalse($isPlanStarted);
    }

}
