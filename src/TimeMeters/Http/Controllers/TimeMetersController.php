<?php declare(strict_types=1);

namespace App\TimeMeters\Http\Controllers;

use App\Shared\Contracts\CommandBusContract;
use App\Shared\Contracts\QueryBusContract;
use App\Shared\Contracts\Validation\ValidatorContract;
use App\Shared\Http\Responses\JsonResponse;
use App\Shared\Http\Responses\SuccessResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\TimeMeters\Application\Create\CreateTimeMeterRules;
use App\TimeMeters\Application\Create\CreateTimeMeterCommand;
use App\TimeMeters\Application\Find\FindAllTimeMetersQuery;
use Ramsey\Uuid\Uuid;

final class TimeMetersController
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
        $this->queryBus = $queryBus;
        $this->validator = $validator;
    }

    public function store(ServerRequestInterface $request): ResponseInterface
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

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $timeMeters = $this->queryBus->handle(
            new FindAllTimeMetersQuery($request->getAttribute("decodedToken")->id()),
        );

        return SuccessResponse::create(
            $timeMeters->map(
                fn($timeMeter) => $timeMeter->toArray(),
            )->toArray(),
        );
    }
}
