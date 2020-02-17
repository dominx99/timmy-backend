<?php declare(strict_types=1);

namespace App\Accounts\Application\Create;

use App\Shared\Contracts\Command;

final class CreateUserCommand implements Command
{
    private string $userId;
    private string $email;
    private string $password;

    public function __construct(string $userId, string $email, string $password)
    {
        $this->userId = $userId;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }
}
