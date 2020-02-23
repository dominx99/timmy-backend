<?php declare(strict_types = 1);

namespace App\Accounts\Application\Validation\Rules;

use Doctrine\DBAL\Connection;
use Respect\Validation\Rules\AbstractRule;

class EmailAvailable extends AbstractRule
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function validate($input)
    {
        $qb = $this
            ->connection
            ->createQueryBuilder()
            ->select('u.id')
            ->from("users", "u")
            ->where("u.email = :email")
            ->setParameter("email", $input);

        return (bool) ! $this->connection->fetchAssoc(
            $qb->getSQL(),
            $qb->getParameters(),
        );
    }
}
