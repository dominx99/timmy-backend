<?php declare(strict_types=1);

namespace App\Accounts\Application\Auth;

use App\Shared\Contracts\Command;

final class VerifyPasswordCommand implements Command
{
    private string $password;
    private string $hash;

    public function __construct(string $password, string $hash)
    {
        $this->password = $password;
        $this->hash     = $hash;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function hash(): string
    {
        return $this->hash;
    }
}
