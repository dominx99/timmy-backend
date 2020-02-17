<?php declare(strict_types=1);

namespace App\Plans\Application\Create;

use App\Shared\Contracts\CommandHandler;
use Doctrine\DBAL\Connection;

final class CreatePlanCommandHandler implements CommandHandler
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function handle(CreatePlanCommand $command): void
    {
        $this
            ->connection
            ->createQueryBuilder()
            ->insert("plans", "p")
            ->values([
                "id" => ":planId",
                "time_meter_id" => ":timeMeterId",
                "user_id" => ":userId",
                "start_date" => ":startDate",
                "end_date" => ":endDate",
                "min_time" => ":minTime",
                "max_time" => ":maxTime",
            ])
            ->setParameters([
                "planId"      => $command->planId(),
                "timeMeterId" => $command->timeMeterId(),
                "userId"      => $command->userId(),
                "startDate"   => $command->startDate(),
                "endDate"     => $command->endDate(),
                "minTime"     => $command->minTime(),
                "maxTime"     => $command->maxTime(),
            ])
            ->execute();
    }
}
