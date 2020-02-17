<?php declare(strict_types=1);

namespace App\Accounts\Domain;

use App\Shared\Exceptions\BusinessException;

final class UserNotFoundException extends BusinessException
{
    public function __construct()
    {
        $this->message = "User not found.";
    }
}
