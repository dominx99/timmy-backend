<?php declare(strict_types=1);

namespace App\Plans\Application\Delete;

use App\Shared\Contracts\CommandHandler;
use App\Shared\Contracts\ErrorMessageInterface;
use App\Shared\Exceptions\BusinessException;
use Doctrine\DBAL\Connection;

final class DeletePlanCommandHandler implements CommandHandler
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function handle(DeletePlanCommand $command): void
    {
        $result = $this
            ->connection
            ->createQueryBuilder()
            ->delete("plans", "p")
            ->where("p.id = :planId")
            ->setParameter("planId", $command->planId())
            ->execute();

        if (! $result) {
            throw new BusinessException(ErrorMessageInterface::PLAN_DELETE_ERROR);
        }
    }
}
