<?php declare(strict_types=1);

namespace App\Accounts\Application\Find;

use App\Shared\Contracts\Query;

final class FindUserByEmailQuery implements Query
{
    public string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }
}
