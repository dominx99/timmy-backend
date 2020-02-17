<?php declare(strict_types=1);

namespace App\Accounts\Contracts;

interface AuthGuardResolverContract
{
    public function resolve(string $grantType): AuthGuard;
}
