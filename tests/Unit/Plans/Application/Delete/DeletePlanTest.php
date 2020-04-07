<?php declare(strict_types=1);

namespace Tests\Unit\Plans\Application\Create;

use App\Plans\Application\Delete\DeletePlanCommand;
use App\Shared\Contracts\ErrorMessageInterface;
use App\Shared\Exceptions\BusinessException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Tests\BaseTestCase;
use Ramsey\Uuid\Uuid;

final class DetelePlanTest extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->planId = (string) Uuid::uuid4();

        $this->queryBuilder = $this->createMock(QueryBuilder::class);

        $this->queryBuilder->method("delete")->willReturn($this->queryBuilder);
        $this->queryBuilder->method("where")->willReturn($this->queryBuilder);

        $this->queryBuilder
            ->expects($this->once())
            ->method("setParameter")
            ->with("planId", $this->planId)
            ->willReturn($this->queryBuilder);
    }

    /** @test */
    public function that_creates_plan()
    {
        $this->queryBuilder->expects($this->once())->method("execute")->willReturn(1);

        $this->connection = $this->createMock(Connection::class);
        $this->connection->method("createQueryBuilder")->willReturn($this->queryBuilder);

        $this->container->set(Connection::class, $this->connection);

        $this->commandBus->handle(new DeletePlanCommand($this->planId));
    }

    /** @test */
    public function that_throws_exception_on_dbal_fail()
    {
        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage(ErrorMessageInterface::PLAN_DELETE_ERROR);

        $this->queryBuilder->expects($this->once())->method("execute")->willReturn(0);

        $this->connection = $this->createMock(Connection::class);
        $this->connection->method("createQueryBuilder")->willReturn($this->queryBuilder);

        $this->container->set(Connection::class, $this->connection);

        $this->commandBus->handle(new DeletePlanCommand($this->planId));
    }
}
