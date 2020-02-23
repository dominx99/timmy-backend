<?php declare(strict_types=1);

namespace Tests\Unit\Plans\Application\Exist;

use Tests\BaseTestCase;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Connection;
use Ramsey\Uuid\Uuid;
use App\Plans\Application\Exist\PlanExistsInPeriodForTimeMeterQuery;
use Doctrine\DBAL\Driver\ResultStatement;
use Doctrine\DBAL\Query\Expression\ExpressionBuilder;
use PHPUnit\Framework\MockObject\MockObject;

final class PlanExistsInPeriodForTimeMeterTest extends BaseTestCase
{
    private MockObject $connection;
    private MockObject $queryBuilder;
    private MockObject $expressionBuilder;
    private MockObject $statement;

    public function setUp(): void
    {
        parent::setUp();

        $this->userId = (string) Uuid::uuid4();
        $this->timeMeterId = (string) Uuid::uuid4();

        $this->startDate = "2020-02-07 00:00:00";
        $this->endDate = "2020-02 14 23:59:59";

        $this->connection = $this->createMock(Connection::class);
        $this->queryBuilder = $this->createMock(QueryBuilder::class);
        $this->expressionBuilder = $this->createMock(ExpressionBuilder::class);
        $this->statement = $this->createMock(ResultStatement::class);

        $this->queryBuilder
            ->method("select")
            ->willReturn($this->queryBuilder);

        $this->queryBuilder
            ->method("from")
            ->willReturn($this->queryBuilder);

        $this->queryBuilder
            ->expects($this->once())
            ->method("where")
            ->willReturn($this->queryBuilder);

        $this->queryBuilder
            ->expects($this->exactly(2))
            ->method("andWhere")
            ->willReturn($this->queryBuilder);

        $this->queryBuilder
            ->expects($this->exactly(10))
            ->method("expr")
            ->willReturn($this->expressionBuilder);

        $this->queryBuilder
            ->expects($this->once())
            ->method("setParameters")
            ->with([
                "userId"      => $this->userId,
                "timeMeterId" => $this->timeMeterId,
                "startDate"   => $this->startDate,
                "endDate"     => $this->endDate,
            ])
            ->willReturn($this->queryBuilder);

        $this->queryBuilder
            ->method("getParameters")
            ->willReturn([]);
    }

    /** @test */
    public function that_finds_plans()
    {
        $this->statement
            ->expects($this->once())
            ->method("fetch")
            ->willReturn(["exist" => 1]);

        $this->queryBuilder
            ->expects($this->once())
            ->method("execute")
            ->willReturn($this->statement);

        $this->connection->method("createQueryBuilder")->willReturn($this->queryBuilder);

        $this->container->set(Connection::class, $this->connection);

        $result = $this->queryBus->handle(new PlanExistsInPeriodForTimeMeterQuery(
            $this->userId,
            $this->timeMeterId,
            $this->startDate,
            $this->endDate,
        ));

        $this->assertTrue($result);
    }

    /** @test */
    public function that_returns_false()
    {
        $this->statement
            ->expects($this->once())
            ->method("fetch")
            ->willReturn(["exist" => 0]);

        $this->queryBuilder
            ->expects($this->once())
            ->method("execute")
            ->willReturn($this->statement);

        $this->connection->method("createQueryBuilder")->willReturn($this->queryBuilder);

        $this->container->set(Connection::class, $this->connection);

        $result = $this->queryBus->handle(new PlanExistsInPeriodForTimeMeterQuery(
            $this->userId,
            $this->timeMeterId,
            $this->startDate,
            $this->endDate,
        ));

        $this->assertFalse($result);
    }
}
