<?php declare(strict_types=1);

namespace App\Accounts\Application\Create;

use App\Shared\Contracts\CommandHandler;
use Doctrine\DBAL\Connection;

final class CreateUserCommandHandler implements CommandHandler
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function handle(CreateUserCommand $command): void
    {
        $this
            ->connection
            ->createQueryBuilder()
            ->insert("users", "u")
            ->values([
                "id"       => ":id",
                "email"    => ":email",
                "password" => ":password",
            ])
            ->setParameter("id", $command->userId())
            ->setParameter("email", $command->email())
            ->setParameter("password", $command->password())
            ->execute();
    }
}
