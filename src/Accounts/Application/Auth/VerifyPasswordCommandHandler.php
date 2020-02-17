<?php declare(strict_types=1);

namespace App\Accounts\Application\Auth;

use App\Accounts\Domain\AuthenticationException;
use App\Shared\Contracts\CommandHandler;

final class VerifyPasswordCommandHandler implements CommandHandler
{
    public function handle(VerifyPasswordCommand $command): void
    {
        if (! password_verify(
            $command->password(),
            $command->hash(),
        )) {
            throw new AuthenticationException("Wrong credentials.");
        }
    }
}
