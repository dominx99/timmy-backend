<?php declare(strict_types=1);

namespace App\Plans\Http\Controllers;

use App\Plans\Application\Create\CreatePlanCommand;
use App\Shared\Contracts\CommandBusContract;
use App\Shared\Contracts\Validation\ValidatorContract;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use App\Plans\Application\Create\CreatePlanRules;
use App\Shared\Http\Responses\SuccessResponse;

final class PlansController
{
    private CommandBusContract $commandBus;
    private ValidatorContract $validator;

    public function __construct(CommandBusContract $commandBus, ValidatorContract $validator)
    {
        $this->commandBus = $commandBus;
        $this->validator = $validator;
    }

    public function store(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();

        $this->validator->validate($body, new CreatePlanRules());

        $this->commandBus->handle(new CreatePlanCommand(
            (string) Uuid::uuid4(),
            $body["time_meter_id"],
            $request->getAttribute("decodedToken")->id(),
            $body["start_date"],
            $body["end_date"],
            $body["min_time"] ?? null,
            $body["max_time"] ?? null,
        ));

        return SuccessResponse::create();
    }
}
