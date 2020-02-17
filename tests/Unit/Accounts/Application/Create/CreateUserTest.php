<?php declare(strict_types=1);

namespace Tests\Unit\Accounts\Application\Create;

use Tests\BaseTestCase;
use Doctrine\DBAL\Query\QueryBuilder;
use Ramsey\Uuid\Uuid;
use Doctrine\DBAL\Connection;
use App\Accounts\Application\Create\CreateUserCommand;

final class CreateUserTest extends BaseTestCase
{
    /** @test */
    public function that_creates_an_user()
    {
        $userId = (string) Uuid::uuid4();
        $email = "example@test.com";
        $queryBuilder = $this->createMock(QueryBuilder::class);

        $queryBuilder
            ->method("insert")
            ->willReturn($queryBuilder);

        $queryBuilder
            ->method("values")
            ->willReturn($queryBuilder);

        $queryBuilder
            ->expects($this->exactly(3))
            ->method("setParameter")
            ->withConsecutive(
                ["id", $userId],
                ["email", $email],
            )
            ->willReturn($queryBuilder);

        $queryBuilder
            ->expects($this->once())
            ->method("execute")
            ->willReturn(1);

        $connection = $this->createMock(Connection::class);
        $connection->method("createQueryBuilder")->willReturn($queryBuilder);

        $this->container->set(Connection::class, $connection);

        $this->commandBus->handle(new CreateUserCommand(
            $userId,
            $email,
            "secret",
        ));
    }
}
