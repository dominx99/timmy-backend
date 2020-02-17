<?php declare(strict_types=1);

namespace App\Accounts\Infrastructure\Guard;

use App\Accounts\Contracts\AuthGuard;
use App\Accounts\Dto\AuthResult;
use App\Accounts\Application\Find\FindUserByEmailQuery;
use App\Accounts\Application\Auth\VerifyPasswordCommand;
use App\Accounts\Application\Auth\SetAccessTokenOnUserCommand;
use App\Shared\Contracts\CommandBusContract;
use App\Shared\Contracts\QueryBusContract;
use App\Shared\Contracts\Validation\ValidatorContract;
use App\Shared\Infrastructure\JWT\JWTEncoder;
use App\Accounts\Application\Auth\AuthenticateByCredentialsRules;

final class CredentialsGuard implements AuthGuard
{
    private CommandBusContract $commandBus;
    private QueryBusContract $queryBus;
    private ValidatorContract $validator;

    public function __construct(
        CommandBusContract $commandBus,
        QueryBusContract $queryBus,
        ValidatorContract $validator
    ) {
        $this->commandBus = $commandBus;
        $this->queryBus   = $queryBus;
        $this->validator  = $validator;
    }

    public function authenticate(array $params, AuthResult &$result): void
    {
        $this->validator->validate($params, new AuthenticateByCredentialsRules());

        $user = $this->queryBus->handle(new FindUserByEmailQuery($params["email"]));

        $this->commandBus->handle(new VerifyPasswordCommand($params["password"], $user->password()));
        $this->commandBus->handle(new SetAccessTokenOnUserCommand(
            $user->id(),
            (string) JWTEncoder::fromUser($user)
        ));

        $user = $this->queryBus->handle(new FindUserByEmailQuery($params["email"]));

        $result->fill([
            "accessToken" => $user->accessToken(),
        ]);
    }
}
