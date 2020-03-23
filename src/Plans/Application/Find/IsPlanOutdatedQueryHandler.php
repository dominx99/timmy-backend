<?php declare(strict_types=1);

namespace App\Plans\Application\Find;

use App\Shared\Contracts\Calendar\CalendarContract;
use App\Shared\Contracts\QueryHandler;
use Doctrine\DBAL\Connection;

final class IsPlanOutdatedQueryHandler implements QueryHandler
{
    private Connection $connection;
    private CalendarContract $calendar;

    public function __construct(Connection $connection, CalendarContract $calendar)
    {
        $this->connection = $connection;
        $this->calendar = $calendar;
    }

    public function handle(IsPlanOutdatedQuery $query): bool
    {
        $result = $this
            ->connection
            ->createQueryBuilder()
            ->select("COUNT(p.id) as is_outdated")
            ->from("plans", "p")
            ->where("p.id = :planId")
            ->andWhere("p.end_date < :now")
            ->setParameters([
                "planId" => $query->planId(),
                "now"    => $this->calendar->now()->format("Y-m-d H:i:s"),
            ])
            ->execute()
            ->fetch();

        return (bool) $result["is_outdated"];
    }
}
