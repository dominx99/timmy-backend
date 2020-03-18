<?php declare(strict_types=1);

namespace App\Measurements\Application\Update;

use App\Shared\Contracts\Calendar\CalendarContract;
use App\Shared\Contracts\CommandHandler;
use Doctrine\DBAL\Connection;

final class StopMeasurementCommandHandler implements CommandHandler
{
    private Connection $connection;
    private CalendarContract $calendar;

    public function __construct(
        Connection $connection,
        CalendarContract $calendar
    ) {
        $this->connection = $connection;
        $this->calendar = $calendar;
    }

    public function handle(StopMeasurementCommand $command): void
    {
        $this
            ->connection
            ->createQueryBuilder()
            ->update("measurements", "m")
            ->set("m.stopped_at", ":stoppedAt")
            ->set("m.status", ":status")
            ->where("m.id = :measurementId")
            ->setParameters([
                "measurementId" => $command->measurementId(),
                "stoppedAt"     => $this->calendar->now()->format("Y-m-d H:i:s"),
                "status"        => "stopped",
            ])
            ->execute();
    }
}
