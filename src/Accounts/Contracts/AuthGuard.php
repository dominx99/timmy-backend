<?php declare(strict_types=1);

namespace App\Accounts\Contracts;

use App\Accounts\Dto\AuthResult;

interface AuthGuard
{
    public function authenticate(array $params, AuthResult &$result): void;
}
