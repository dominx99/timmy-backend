<?php declare(strict_types=1);

namespace App\TimeMeters\Http\Controllers;

use App\Shared\Contracts\CommandBusContract;
use App\Shared\Contracts\Validation\ValidatorContract;
use App\Shared\Http\Responses\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\TimeMeters\Application\Create\CreateTimeMeterRules;
use App\TimeMeters\Application\Create\CreateTimeMeterCommand;
use Ramsey\Uuid\Uuid;

final class TimeMetersController
{
    private CommandBusContract $commandBus;
    private ValidatorContract $validator;

    public function __construct(
        CommandBusContract $commandBus,
        ValidatorContract $validator
    ) {
        $this->commandBus = $commandBus;
        $this->validator = $validator;
    }

    public function store(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $body = $request->getParsedBody();

        $this->validator->validate($body, new CreateTimeMeterRules());

        $this->commandBus->handle(
            new CreateTimeMeterCommand(
                (string) Uuid::uuid4(),
                $request->getAttribute("decodedToken")->id(),
                $body["name"]
            )
        );

        return JsonResponse::create(["status" => "success"]);
    }
}
