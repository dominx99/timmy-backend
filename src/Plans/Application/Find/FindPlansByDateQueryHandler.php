<?php declare(strict_types=1);

namespace App\Plans\Application\Find;

use App\Measurements\Application\Find\FindMeasurementsByPlanQuery;
use App\Shared\Contracts\QueryHandler;
use Doctrine\DBAL\Connection;
use App\Shared\Exceptions\BusinessException;
use App\Plans\Domain\PlanView;
use App\Plans\Domain\Plans;
use App\Shared\Contracts\QueryBusContract;
use App\TimeMeters\Domain\TimeMeterView;

final class FindPlansByDateQueryHandler implements QueryHandler
{
    private Connection $connection;
    private QueryBusContract $queryBus;

    public function __construct(Connection $connection, QueryBusContract $queryBus)
    {
        $this->connection = $connection;
        $this->queryBus   = $queryBus;
    }

    public function handle(FindPlansByDateQuery $query): Plans
    {
        $qb = $this
            ->connection
            ->createQueryBuilder();

        $qb
            ->select("*", "p.id as id")
            ->from("plans", "p")
            ->join("p", "time_meters", "tm", "p.time_meter_id = tm.id")
            ->having("p.user_id = :userId")
            ->andHaving("p.start_date <= :date")
            ->andHaving("p.end_date >= :date")
            ->orderBy("p.created_at")
            ->setParameter("userId", $query->userId())
            ->setParameter("date", $query->date());

        $plans = $this->connection->fetchAll(
            $qb->getSQL(),
            $qb->getParameters()
        );

        if ($plans === false) {
            throw new BusinessException("Could not fetch plans.");
        }

        return new Plans(array_map(function ($row) {
            $plan = PlanView::create($row);
            $plan->setTimeMeter(TimeMeterView::create(array_merge($row, ["id" => $row["time_meter_id"]])));
            $plan->setMeasurements(
                $this->queryBus->handle(new FindMeasurementsByPlanQuery($row["id"]))
            );

            return $plan;
        }, $plans));
    }
}
