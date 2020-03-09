<?php declare(strict_types=1);

namespace App\Plans\Application\Find;

use App\Shared\Contracts\QueryHandler;
use Doctrine\DBAL\Connection;

final class IsPlanStartedQueryHandler implements QueryHandler
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function handle(IsPlanStartedQuery $query): bool
    {
        $result = $this
            ->connection
            ->createQueryBuilder()
            ->select("COUNT(m.id) as is_started")
            ->from("measurements", "m")
            ->where("m.plan_id = :planId")
            ->andWhere("m.status = :status")
            ->setParameters([
                "planId" => $query->planId(),
                "status" => "started",
            ])
            ->execute()
            ->fetch();

        return (bool) $result["is_started"];
    }
}
