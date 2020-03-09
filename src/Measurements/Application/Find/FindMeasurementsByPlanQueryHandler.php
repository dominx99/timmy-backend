<?php declare(strict_types=1);

namespace App\Measurements\Application\Find;

use App\Shared\Contracts\QueryHandler;
use Doctrine\DBAL\Connection;
use App\Measurements\Domain\Measurements;
use App\Shared\Exceptions\BusinessException;
use App\Measurements\Domain\MeasurementView;

final class FindMeasurementsByPlanQueryHandler implements QueryHandler
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function handle(FindMeasurementsByPlanQuery $query): Measurements
    {
        $qb = $this
            ->connection
            ->createQueryBuilder();

        $qb
            ->select("*")
            ->from("measurements", "m")
            ->where("m.plan_id = :planId")
            ->orderBy("m.created_at")
            ->setParameter("planId", $query->planId());

        $measurements = $this->connection->fetchAll(
            $qb->getSQL(),
            $qb->getParameters()
        );

        if ($measurements === false) {
            throw new BusinessException("Could not fetch measurements.");
        }

        return new Measurements(array_map(function ($row) {
            return MeasurementView::create($row);
        }, $measurements));
    }
}
