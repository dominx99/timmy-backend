<?php declare(strict_types=1);

namespace App\Plans\Http\Controllers;

use App\Plans\Application\Create\CreatePlanCommand;
use App\Shared\Contracts\CommandBusContract;
use App\Shared\Contracts\Validation\ValidatorContract;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use App\Plans\Application\Create\CreatePlanRules;
use App\Shared\Contracts\ErrorMessageInterface;
use App\Shared\Contracts\QueryBusContract;
use App\Shared\Exceptions\BusinessException;
use App\Shared\Http\Responses\SuccessResponse;
use App\Plans\Application\Exist\PlanExistsInPeriodForTimeMeterQuery;
use Fig\Http\Message\StatusCodeInterface;

final class PlansController
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

        $this->validator->validate($body, new CreatePlanRules());

        $exists = $this->queryBus->handle(new PlanExistsInPeriodForTimeMeterQuery(
            $request->getAttribute("decodedToken")->id(),
            $body["timeMeterId"],
            $body["startDate"],
            $body["endDate"],
        ));

        if ($exists) {
            throw new BusinessException(
                ErrorMessageInterface::PLAN_IN_EXISTING_PERIOD,
                StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
            );
        }

        $this->commandBus->handle(new CreatePlanCommand(
            (string) Uuid::uuid4(),
            $body["timeMeterId"],
            $request->getAttribute("decodedToken")->id(),
            $body["startDate"],
            $body["endDate"],
            ! empty($body["minTime"]) ? $body["minTime"] : null,
            ! empty($body["maxTime"]) ? $body["maxTime"] : null,
        ));

        return SuccessResponse::create();
    }
}
