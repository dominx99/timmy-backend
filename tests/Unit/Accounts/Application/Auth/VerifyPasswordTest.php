<?php declare(strict_types=1);

namespace Tests\Unit\Accounts\Application\Auth;

use App\Accounts\Application\Auth\VerifyPasswordCommand;
use Tests\BaseTestCase;
use App\Accounts\Domain\AuthenticationException;

final class VerifyPasswordTest extends BaseTestCase
{
    /** @test */
    public function that_works_for_valid_data()
    {
        $password = "secret";
        $hash = password_hash($password, PASSWORD_BCRYPT);

        $this->commandBus->handle(new VerifyPasswordCommand($password, $hash));

        $this->addToAssertionCount(1);
    }

    /** @test */
    public function that_throws_not_valid_password_exception_on_not_valid_data()
    {
        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage("Wrong credentials.");

        $hash = password_hash("not-a-secret", PASSWORD_BCRYPT);

        $this->commandBus->handle(new VerifyPasswordCommand("secret", $hash));
    }
}
