<?php declare(strict_types=1);

namespace App\Measurements\Application\Create;

use App\Shared\Contracts\CommandHandler;
use Doctrine\DBAL\Connection;

final class CreateMeasurementForPlanCommandHandler implements CommandHandler
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function handle(CreateMeasurementForPlanCommand $command): void
    {
        $this
            ->connection
            ->createQueryBuilder()
            ->insert("measurements", "m")
            ->values([
                "id"      => ":measurementId",
                "plan_id" => ":planId",
            ])
            ->setParameters([
                "measurementId" => $command->measurementId(),
                "planId"        => $command->planId(),
            ])
            ->execute();
    }
}
