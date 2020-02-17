<?php declare(strict_types=1);

namespace App\Accounts\Domain;

use App\Shared\Exceptions\BusinessException;

final class AuthenticationException extends BusinessException
{
    public function __construct(string $message = "Could not authenticate.")
    {
        $this->message = $message;
    }
}
