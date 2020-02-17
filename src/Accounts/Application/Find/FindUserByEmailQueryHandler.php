<?php declare(strict_types=1);

namespace App\Accounts\Application\Find;

use Doctrine\DBAL\Connection;
use App\Accounts\Application\Views\UserView;
use App\Shared\Contracts\QueryHandler;
use App\Accounts\Domain\UserNotFoundException;

final class FindUserByEmailQueryHandler implements QueryHandler
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function handle(FindUserByEmailQuery $query): UserView
    {
        $qb = $this
            ->connection
            ->createQueryBuilder();

        $qb
            ->select('*')
            ->from('users', 'u')
            ->where('u.email = :email')
            ->setParameter('email', $query->email);

        $user = $this->connection->fetchAssoc(
            $qb->getSQL(),
            $qb->getParameters()
        );

        if (! $user) {
            throw new UserNotFoundException();
        }

        return UserView::create($user);
    }
}
