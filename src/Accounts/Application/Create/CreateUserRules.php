<?php declare(strict_types=1);

namespace App\Accounts\Application\Create;

use App\Shared\Contracts\Validation\Rules;
use Doctrine\DBAL\Connection;
use Respect\Validation\Validator as v;

final class CreateUserRules implements Rules
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function rules(): array
    {
        return [
            "email"    => v::notEmpty()->email()->EmailAvailable($this->connection),
            "password" => v::notEmpty(),
        ];
    }
}
