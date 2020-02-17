<?php declare(strict_types=1);

namespace Tests\Unit\Accounts\Application\Auth;

use App\Shared\Infrastructure\JWT\JWTEncoder;
use Tests\BaseTestCase;
use Doctrine\DBAL\Query\QueryBuilder;
use Ramsey\Uuid\Uuid;
use Doctrine\DBAL\Connection;
use App\Accounts\Application\Auth\SetAccessTokenOnUserCommand;

final class SetAccessTokenOnUserTest extends BaseTestCase
{
    /** @test */
    public function that_executes_required_methods()
    {
        $userId = (string) Uuid::uuid4();
        $token = (string) JWTEncoder::fromArray(["id" => $userId]);

        $queryBuilder = $this->createMock(QueryBuilder::class);

        $queryBuilder->method("update")->willReturn($queryBuilder);
        $queryBuilder->method("set")->willReturn($queryBuilder);
        $queryBuilder->method("where")->willReturn($queryBuilder);

        $queryBuilder
            ->expects($this->exactly(2))
            ->method("setParameter")
            ->withConsecutive(
                ["token", $token],
                ["id", $userId],
            )
            ->willReturn($queryBuilder);


        $connection = $this->createMock(Connection::class);
        $connection->method("createQueryBuilder")->willReturn($queryBuilder);

        $this->container->set(Connection::class, $connection);

        $this->commandBus->handle(new SetAccessTokenOnUserCommand($userId, $token));
    }
}
