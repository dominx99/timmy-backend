<?php declare(strict_types=1);

namespace App\TimeMeters\Application\Create;

use App\Shared\Contracts\CommandHandler;
use Doctrine\DBAL\Connection;

final class CreateTimeMeterCommandHandler implements CommandHandler
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function handle(CreateTimeMeterCommand $command): void
    {
        $this
            ->connection
            ->createQueryBuilder()
            ->insert("time_meters")
            ->values([
                "id"      => ":id",
                "name"    => ":name",
                "user_id" => ":user_id",
            ])
            ->setParameter("id", $command->timeMeterId)
            ->setParameter("name", $command->name)
            ->setParameter("user_id", $command->userId)
            ->execute();
    }
}
