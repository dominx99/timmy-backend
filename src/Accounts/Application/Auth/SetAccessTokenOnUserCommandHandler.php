<?php declare(strict_types=1);

namespace App\Accounts\Application\Auth;

use App\Shared\Contracts\CommandHandler;
use Doctrine\DBAL\Connection;

final class SetAccessTokenOnUserCommandHandler implements CommandHandler
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function handle(SetAccessTokenOnUserCommand $command): void
    {
        $this
            ->connection
            ->createQueryBuilder()
            ->update("users", "u")
            ->set("u.access_token", ":token")
            ->where("u.id = :id")
            ->setParameter("token", $command->token())
            ->setParameter("id", $command->userId())
            ->execute();
    }
}
