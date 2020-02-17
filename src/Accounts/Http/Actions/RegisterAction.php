<?php declare(strict_types=1);

namespace App\Accounts\Http\Actions;

use App\Accounts\Application\Create\CreateUserCommand;
use App\Accounts\Application\Create\CreateUserRules;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Accounts\Dto\AuthResult;
use App\Accounts\Infrastructure\Guard\CredentialsGuard;
use App\Shared\Contracts\CommandBusContract;
use App\Shared\Contracts\Validation\ValidatorContract;
use App\Shared\Http\Responses\SuccessResponse;
use Ramsey\Uuid\Uuid;

final class RegisterAction
{
    private ValidatorContract $validator;
    private CommandBusContract $commandBus;
    private CredentialsGuard $credentialsGuard;

    public function __construct(
        ValidatorContract $validator,
        CommandBusContract $commandBus,
        CredentialsGuard $credentialsGuard
    ) {
        $this->validator        = $validator;
        $this->commandBus       = $commandBus;
        $this->credentialsGuard = $credentialsGuard;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();

        $this->validator->validate($body, new CreateUserRules());

        $this->commandBus->handle(new CreateUserCommand(
            (string) Uuid::uuid4(),
            $body["email"],
            $body["password"],
        ));

        $result = new AuthResult();

        $this->credentialsGuard->authenticate($body, $result);

        return SuccessResponse::create($result->toArray());
    }
}
