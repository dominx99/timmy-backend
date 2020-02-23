<?php declare(strict_types=1);

namespace Tests\Unit\TimeMeters\Application\Find;

use App\Shared\Exceptions\BusinessException;
use Tests\BaseTestCase;
use App\TimeMeters\Application\Find\FindAllTimeMetersQuery;
use App\TimeMeters\Domain\TimeMeters;
use App\TimeMeters\Domain\TimeMeterView;
use Doctrine\DBAL\Query\QueryBuilder;
use Ramsey\Uuid\Uuid;
use Doctrine\DBAL\Connection;

final class FindAllTimeMetersTest extends BaseTestCase
{
    /** @test */
    public function that_executes_required_methods()
    {
        $userId = (string) Uuid::uuid4();

        $expectedTimeMeters = [
            [
                "id"      => (string) Uuid::uuid4(),
                "user_id" => $userId,
                "name"    => "Work",
            ],
            [
                "id"      => (string) Uuid::uuid4(),
                "user_id" => $userId,
                "name"    => "Work",
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
            ->method("where")
            ->willReturn($queryBuilder);

        $queryBuilder
            ->method("orderBy")
            ->willReturn($queryBuilder);

        $queryBuilder
            ->expects($this->once())
            ->method("setParameter")
            ->with("userId", $userId)
            ->willReturn($queryBuilder);

        $queryBuilder
            ->method("getParameters")
            ->willReturn([]);

        $connection = $this->createMock(Connection::class);
        $connection->method("createQueryBuilder")->willReturn($queryBuilder);

        $connection
            ->expects($this->once())
            ->method("fetchAll")
            ->willReturn($expectedTimeMeters);

        $this->container->set(Connection::class, $connection);

        $timeMeters = $this->queryBus->handle(new FindAllTimeMetersQuery($userId));

        $expectedTimeMeters = new TimeMeters(array_map(
            fn ($timeMeter) => TimeMeterView::create($timeMeter),
            $expectedTimeMeters
        ));

        $timeMeters = $timeMeters->toArray();

        foreach ($expectedTimeMeters->toArray() as $key => $expectedTimeMeter) {
            $this->assertSame($expectedTimeMeter->id(), $timeMeters[$key]->id());
            $this->assertSame($expectedTimeMeter->userId(), $timeMeters[$key]->userId());
            $this->assertSame($expectedTimeMeter->name(), $timeMeters[$key]->name());
        }
    }

    /** @test */
    public function that_throws_exception()
    {
        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage("Could not fetch time meters.");

        $queryBuilder = $this->createMock(QueryBuilder::class);

        $queryBuilder
            ->method("select")
            ->willReturn($queryBuilder);

        $queryBuilder
            ->method("from")
            ->willReturn($queryBuilder);

        $queryBuilder
            ->method("where")
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

        $this->queryBus->handle(new FindAllTimeMetersQuery((string) Uuid::uuid4()));
    }
}
