<?php declare(strict_types=1);

namespace App\Plans\Application\Exist;

use App\Shared\Contracts\QueryHandler;
use Doctrine\DBAL\Connection;

final class PlanExistsInPeriodForTimeMeterQueryHandler implements QueryHandler
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function handle(PlanExistsInPeriodForTimeMeterQuery $query): bool
    {
        $qb = $this
            ->connection
            ->createQueryBuilder();

        $result = $qb
            ->select("count(p.id) as exist")
            ->from("plans", "p")
            ->where("p.user_id = :userId")
            ->andWhere("p.time_meter_id = :timeMeterId")
            ->andWhere($qb->expr()->orX(
                $qb->expr()->andX(
                    $qb->expr()->lte("p.start_date", ":startDate"),
                    $qb->expr()->gte("p.end_date", ":startDate"),
                ),
                $qb->expr()->andX(
                    $qb->expr()->lte("p.start_date", ":endDate"),
                    $qb->expr()->gte("p.end_date", ":endDate"),
                ),
                $qb->expr()->andX(
                    $qb->expr()->gte("p.start_date", ":startDate"),
                    $qb->expr()->lte("p.end_date", ":endDate"),
                ),
            ))
            ->setParameters([
                "userId"      => $query->userId(),
                "timeMeterId" => $query->timeMeterId(),
                "startDate"   => $query->startDate(),
                "endDate"     => $query->endDate(),
            ])
            ->execute()
            ->fetch();

        return (bool) $result["exist"];
    }
}
