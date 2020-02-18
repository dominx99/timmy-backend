<?php declare(strict_types=1);

namespace App\TimeMeters\Application\Find;

use App\Shared\Contracts\QueryHandler;
use App\Shared\Exceptions\BusinessException;
use Doctrine\DBAL\Connection;
use App\TimeMeters\Domain\TimeMeters;
use App\TimeMeters\Domain\TimeMeterView;

final class FindAllTimeMetersQueryHandler implements QueryHandler
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function handle(FindAllTimeMetersQuery $query): TimeMeters
    {
        $qb = $this
            ->connection
            ->createQueryBuilder();

        $qb
            ->select("*")
            ->from("time_meters", "tm")
            ->where("tm.user_id = :userId")
            ->setParameter("userId", $query->userId());

        $timeMeters = $this->connection->fetchAll(
            $qb->getSQL(),
            $qb->getParameters()
        );

        if ($timeMeters === false) {
            throw new BusinessException("Could not fetch time meters.");
        }

        $timeMeters = array_map(fn($timeMeter) => TimeMeterView::create($timeMeter), $timeMeters);

        return new TimeMeters($timeMeters);
    }
}
